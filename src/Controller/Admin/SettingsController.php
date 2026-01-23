<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\Email\EmailTransportFactory;
use Cake\Http\Response;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $settings = $this->paginate($this->Settings);
        $this->set(compact('settings'));
    }

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $setting = $this->Settings->get($id, contain: []);
        $this->set(compact('setting'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEmptyEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting "{0}" has been saved successfully.', $setting->key_name));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please check the form and try again.'));
        }

        $this->set([
            'setting' => $setting,
            'title' => 'Add New Setting',
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $setting = $this->Settings->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setting could not be saved. Please, try again.'));
        }
        $this->set(compact('setting'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__('The setting has been deleted.'));
        } else {
            $this->Flash->error(__('The setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Email settings method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function email()
    {
        $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $settingsToSave = [
                'PLATFORMA_EMAIL' => 'Platforma de email (RESEND sau SMTP)',
                'SMTP_HOST' => 'Server SMTP',
                'SMTP_PORT' => 'Port SMTP',
                'SMTP_USER' => 'Utilizator SMTP',
                'SMTP_PASSWORD' => 'Parolă SMTP',
                'SMTP_TLS' => 'TLS pentru SMTP',
                'EMAIL_FROM_ADDRESS' => 'Adresa de email expeditor',
                'EMAIL_FROM_NAME' => 'Numele expeditorului',
            ];

            $success = true;
            foreach ($settingsToSave as $key => $description) {
                if (isset($data[$key])) {
                    if (!$siteSettings->setValue($key, (string)$data[$key], $description)) {
                        $success = false;
                    }
                }
            }

            // Clear cached transport when settings change
            EmailTransportFactory::clearCache();

            if ($success) {
                $this->Flash->success(__('Setările de email au fost salvate.'));
            } else {
                $this->Flash->error(__('Unele setări nu au putut fi salvate.'));
            }

            return $this->redirect(['action' => 'email']);
        }

        // Load current settings
        $emailSettings = [
            'PLATFORMA_EMAIL' => $siteSettings->getValue('PLATFORMA_EMAIL', 'RESEND'),
            'SMTP_HOST' => $siteSettings->getValue('SMTP_HOST', ''),
            'SMTP_PORT' => $siteSettings->getValue('SMTP_PORT', '587'),
            'SMTP_USER' => $siteSettings->getValue('SMTP_USER', ''),
            'SMTP_PASSWORD' => $siteSettings->getValue('SMTP_PASSWORD', ''),
            'SMTP_TLS' => $siteSettings->getValue('SMTP_TLS', '1'),
            'EMAIL_FROM_ADDRESS' => $siteSettings->getValue('EMAIL_FROM_ADDRESS', 'noreply@smupitesti.online'),
            'EMAIL_FROM_NAME' => $siteSettings->getValue('EMAIL_FROM_NAME', 'Spitalul Militar Pitesti'),
        ];

        $platforms = EmailTransportFactory::getAvailablePlatforms();
        $currentPlatform = EmailTransportFactory::getCurrentPlatform();

        $this->set(compact('emailSettings', 'platforms', 'currentPlatform'));
    }

    /**
     * Test email method
     *
     * @return \Cake\Http\Response|null Redirects back to email settings
     */
    public function testEmail(): ?Response
    {
        $this->request->allowMethod(['post']);

        $testEmailAddress = $this->request->getData('test_email');

        if (empty($testEmailAddress) || !filter_var($testEmailAddress, FILTER_VALIDATE_EMAIL)) {
            $this->Flash->error(__('Vă rugăm introduceți o adresă de email validă.'));

            return $this->redirect(['action' => 'email']);
        }

        try {
            $transport = EmailTransportFactory::getTransport();
            $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');

            $fromEmail = (string)$siteSettings->getValue('EMAIL_FROM_ADDRESS', 'noreply@smupitesti.online');
            $fromName = (string)$siteSettings->getValue('EMAIL_FROM_NAME', 'Spitalul Militar Pitesti');
            $platform = EmailTransportFactory::getCurrentPlatform();

            $html = '<html><body>';
            $html .= '<h1>Email de test</h1>';
            $html .= '<p>Acest email a fost trimis pentru a testa configurarea platformei de email.</p>';
            $html .= '<p><strong>Platformă:</strong> ' . h($platform) . '</p>';
            $html .= '<p><strong>Data:</strong> ' . date('d.m.Y H:i:s') . '</p>';
            $html .= '<p>Dacă primiți acest email, configurarea funcționează corect.</p>';
            $html .= '</body></html>';

            $text = "Email de test\n\n";
            $text .= "Acest email a fost trimis pentru a testa configurarea platformei de email.\n\n";
            $text .= "Platformă: {$platform}\n";
            $text .= 'Data: ' . date('d.m.Y H:i:s') . "\n\n";
            $text .= "Dacă primiți acest email, configurarea funcționează corect.\n";

            $result = $transport->send(
                $testEmailAddress,
                'Email de test - ' . $fromName,
                $html,
                $text,
                $fromEmail,
                $fromName,
            );

            if ($result) {
                $msg = __('Email de test trimis cu succes către {0} folosind {1}.', $testEmailAddress, $platform);
                $this->Flash->success($msg);
                Log::info("Test email sent successfully to {$testEmailAddress} via {$platform}");
            } else {
                $this->Flash->error(__('Emailul de test nu a putut fi trimis.'));
            }
        } catch (Exception $e) {
            $this->Flash->error(__('Eroare la trimiterea emailului de test: {0}', $e->getMessage()));
            Log::error('Test email error: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'email']);
    }
}
