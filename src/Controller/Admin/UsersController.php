<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Mailer\AdminMailer;
use App\Service\LoginSecurityService;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Admin Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * Before filter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event): ?Response
    {
        // For login/logout actions, allow unauthenticated access
        // without going through full Admin beforeFilter checks
        $action = $this->request->getParam('action');
        $unauthenticatedActions = ['login', 'logout', 'verify2fa', 'resend2fa'];
        if (in_array($action, $unauthenticatedActions)) {
            // Call Cake base controller beforeFilter
            Controller::beforeFilter($event);

            // Explicitly allow these actions without authentication
            $this->Authentication->allowUnauthenticated($unauthenticatedActions);

            // Unlock login/verify2fa/resend2fa from FormProtection to prevent token expiry errors
            // when forms are left open for extended periods.
            // CSRF protection is still active via CsrfProtectionMiddleware.
            if ($this->components()->has('FormProtection')) {
                $this->FormProtection->setConfig('unlockedActions', ['login', 'verify2fa', 'resend2fa']);
            }

            return null;
        }

        parent::beforeFilter($event);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful login, renders view otherwise.
     */
    public function login(): ?Response
    {
        // Use minimal login layout without sidebar
        $this->viewBuilder()->setLayout('admin_login');

        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        // Initialize login security service
        $loginSecurity = new LoginSecurityService();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $email = $data['email'] ?? '';
            $ipAddress = $this->request->clientIp();
            $userAgent = $this->request->getHeaderLine('User-Agent');

            // Debug logging (without PII)
            $this->log('Login attempt received', 'debug');

            // Check if login is allowed (rate limiting)
            $loginCheck = $loginSecurity->isLoginAllowed($email, $ipAddress);
            if (!$loginCheck['allowed']) {
                $this->Flash->error(__($loginCheck['reason']));

                return null;
            }

            // Check authentication result
            if ($result && $result->isValid()) {
                // Record successful login and clear failed attempts
                $loginSecurity->recordLoginAttempt($email, $ipAddress, $userAgent, true);
                $loginSecurity->clearAttemptsOnSuccess($email, $ipAddress);

                // Load user with email2FA field from database
                $identity = $this->Authentication->getIdentity();
                $user = $this->Users->get($identity->getIdentifier());

                // Check if 2FA is enabled for this user
                if (!empty($user->email2FA)) {
                    // Generate 6-digit code
                    $code = (string)random_int(100000, 999999);

                    // Store 2FA data in session (hashed code)
                    $session = $this->request->getSession();
                    $session->write('Auth2FA', [
                        'user_id' => $user->id,
                        'code_hash' => password_hash($code, PASSWORD_DEFAULT),
                        'expires' => time() + 300,
                        'attempts' => 0,
                        'email' => $user->email2FA,
                        'last_resend' => 0,
                    ]);

                    // Logout - identity will be set after 2FA verification
                    $this->Authentication->logout();

                    // Send 2FA code via email
                    $mailer = new AdminMailer();
                    $mailer->sendTwoFactorCode($user, $code);

                    $this->Flash->success(__('Un cod de verificare a fost trimis pe email.'));

                    return $this->redirect(['action' => 'verify2fa']);
                }

                // No 2FA - standard redirect to dashboard
                $redirect = $this->validateRedirectUrl($this->request->getQuery('redirect'));

                return $this->redirect($redirect);
            }

            // Authentication failed - record the attempt
            $loginSecurity->recordLoginAttempt($email, $ipAddress, $userAgent, false);
            $this->Flash->error(__('Invalid email or password'));

            return null;
        }

        // Redirect if already logged in (GET request)
        if ($result && $result->isValid()) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
                'prefix' => 'Admin',
            ]);
        }

        return null;
    }

    /**
     * Validate redirect URL to prevent open redirect vulnerability.
     * Only allows internal admin paths.
     *
     * @param mixed $redirect The redirect value from query string
     * @return array The validated redirect array
     */
    private function validateRedirectUrl(mixed $redirect): array
    {
        $default = ['controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'Admin'];
        // Only allow Admin prefix routes - always redirect to dashboard for security
        return $default;
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void Redirects to login page.
     */
    public function logout(): ?Response
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            $this->Flash->success(__('You have been logged out.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => 'Admin']);
    }

    /**
     * Verify 2FA code
     *
     * @return \Cake\Http\Response|null|void Redirects on success, renders view otherwise.
     */
    public function verify2fa(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_login');
        $this->request->allowMethod(['get', 'post']);

        $session = $this->request->getSession();
        $auth2fa = $session->read('Auth2FA');

        // No 2FA session - redirect to login
        if (empty($auth2fa) || empty($auth2fa['user_id'])) {
            return $this->redirect(['action' => 'login']);
        }

        // Mask email for display (e.g., a****@email.com)
        $maskedEmail = $this->maskEmail($auth2fa['email']);
        $this->set('maskedEmail', $maskedEmail);

        if ($this->request->is('post')) {
            // Check if session has expired
            if (time() > $auth2fa['expires']) {
                $session->delete('Auth2FA');
                $this->Flash->error(__('Codul de verificare a expirat. Vă rugăm să vă autentificați din nou.'));

                return $this->redirect(['action' => 'login']);
            }

            // Check max attempts (5)
            if ($auth2fa['attempts'] >= 5) {
                $session->delete('Auth2FA');
                $this->Flash->error(__('Prea multe încercări eșuate. Vă rugăm să vă autentificați din nou.'));

                return $this->redirect(['action' => 'login']);
            }

            $submittedCode = (string)$this->request->getData('code');

            // Verify code
            if (password_verify($submittedCode, $auth2fa['code_hash'])) {
                // Code is valid - load user and set identity
                $user = $this->Users->get($auth2fa['user_id']);
                $this->Authentication->setIdentity($user);

                // Clean up 2FA session
                $session->delete('Auth2FA');

                $this->Flash->success(__('Autentificare reușită.'));

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index',
                    'prefix' => 'Admin',
                ]);
            }

            // Invalid code - increment attempts
            $auth2fa['attempts']++;
            $session->write('Auth2FA', $auth2fa);

            $remainingAttempts = 5 - $auth2fa['attempts'];
            if ($remainingAttempts > 0) {
                $this->Flash->error(__('Cod invalid. Mai aveți {0} încercări.', $remainingAttempts));
            } else {
                $session->delete('Auth2FA');
                $this->Flash->error(__('Prea multe încercări eșuate. Vă rugăm să vă autentificați din nou.'));

                return $this->redirect(['action' => 'login']);
            }
        }

        return null;
    }

    /**
     * Resend 2FA code
     *
     * @return \Cake\Http\Response|null|void Redirects to verify2fa.
     */
    public function resend2fa(): ?Response
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $auth2fa = $session->read('Auth2FA');

        // No 2FA session - redirect to login
        if (empty($auth2fa) || empty($auth2fa['user_id'])) {
            return $this->redirect(['action' => 'login']);
        }

        // Rate limiting: max 1 resend per 60 seconds
        $lastResend = $auth2fa['last_resend'] ?? 0;
        if (time() - $lastResend < 60) {
            $remaining = 60 - (time() - $lastResend);
            $this->Flash->error(__('Vă rugăm să așteptați {0} secunde înainte de a retrimite codul.', $remaining));

            return $this->redirect(['action' => 'verify2fa']);
        }

        // Generate new code
        $code = (string)random_int(100000, 999999);

        // Update session with new code, reset expiry and attempts
        $auth2fa['code_hash'] = password_hash($code, PASSWORD_DEFAULT);
        $auth2fa['expires'] = time() + 300;
        $auth2fa['attempts'] = 0;
        $auth2fa['last_resend'] = time();
        $session->write('Auth2FA', $auth2fa);

        // Send new code via email
        $user = $this->Users->get($auth2fa['user_id']);
        $mailer = new AdminMailer();
        $mailer->sendTwoFactorCode($user, $code);

        $this->Flash->success(__('Un cod nou de verificare a fost trimis pe email.'));

        return $this->redirect(['action' => 'verify2fa']);
    }

    /**
     * Mask email address for display (e.g., a****@email.com)
     *
     * @param string $email The email address to mask
     * @return string The masked email
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '****@****';
        }

        $local = $parts[0];
        $domain = $parts[1];

        if (strlen($local) <= 1) {
            $masked = $local . '****';
        } else {
            $masked = $local[0] . str_repeat('*', min(4, strlen($local) - 1));
        }

        return $masked . '@' . $domain;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(): ?Response
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));

        return null;
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null): ?Response
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));

        return null;
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));

        return null;
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null): ?Response
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));

        return null;
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
