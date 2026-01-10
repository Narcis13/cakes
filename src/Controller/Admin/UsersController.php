<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\LoginSecurityService;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;

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
    public function beforeFilter(EventInterface $event)
    {
        // For login/logout actions, allow unauthenticated access
        // without going through full Admin beforeFilter checks
        $action = $this->request->getParam('action');
        if (in_array($action, ['login', 'logout'])) {
            // Call Cake base controller beforeFilter
            Controller::beforeFilter($event);

            // Explicitly allow login/logout without authentication
            $this->Authentication->allowUnauthenticated(['login', 'logout']);

            return;
        }

        parent::beforeFilter($event);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful login, renders view otherwise.
     */
    public function login()
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

                // Validate redirect URL - only allow internal admin paths
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

        // If no redirect or not a string, use default
        if (empty($redirect) || !is_string($redirect)) {
            return $default;
        }

        // Only allow paths starting with /smupa1881/ (admin prefix)
        if (str_starts_with($redirect, '/smupa1881/')) {
            return $redirect;
        }

        return $default;
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void Redirects to login page.
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            $this->Flash->success(__('You have been logged out.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => 'Admin']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
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
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
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
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
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
