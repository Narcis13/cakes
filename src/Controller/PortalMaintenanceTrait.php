<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\PortalMaintenanceService;
use ArrayAccess;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * PortalMaintenanceTrait
 *
 * Shared guard for the patient facing controllers (portal + online booking).
 * When maintenance mode is switched on from the admin panel every request is
 * answered with the maintenance notice instead of the real page.
 *
 * Call {@see self::checkPortalMaintenance()} from `beforeFilter()` and return
 * its result when it is not null.
 *
 * @property \Cake\Controller\Component\FlashComponent $Flash
 * @property \Cake\Http\ServerRequest $request
 * @property \Cake\Http\Response $response
 */
trait PortalMaintenanceTrait
{
    /**
     * Stop the request when the portal is in maintenance mode.
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @param array<string> $allowedActions Actions that stay reachable during maintenance.
     * @return \Cake\Http\Response|null The maintenance response, or null to continue normally.
     */
    protected function checkPortalMaintenance(EventInterface $event, array $allowedActions = []): ?Response
    {
        $action = (string)$this->request->getParam('action');
        if (in_array($action, $allowedActions, true)) {
            return null;
        }

        $maintenance = new PortalMaintenanceService();
        if (!$maintenance->isEnabled()) {
            return null;
        }

        // Admins and staff keep access so they can verify the portal while it is closed
        if ($this->hasAdminSession()) {
            if (!$this->wantsJsonResponse()) {
                $this->Flash->warning(__(
                    'Portalul este în MENTENANȚĂ. Vedeți conținutul real pentru că sunteți '
                    . 'autentificat în panoul de administrare; pacienții văd mesajul de mentenanță.',
                ));
            }

            return null;
        }

        // Nothing else should run for this request
        $event->stopPropagation();

        if ($this->wantsJsonResponse()) {
            $body = json_encode([
                'success' => false,
                'maintenance' => true,
                'message' => $maintenance->getMessage(),
            ], JSON_UNESCAPED_UNICODE);

            return $this->response
                ->withStatus(503)
                ->withType('application/json')
                ->withStringBody((string)$body);
        }

        $this->viewBuilder()->setLayout('default');
        $this->set([
            'maintenanceTitle' => $maintenance->getTitle(),
            'maintenanceMessage' => $maintenance->getMessage(),
            'maintenanceEta' => $maintenance->getEta(),
        ]);

        return $this->render('/Portal/maintenance')->withStatus(503);
    }

    /**
     * Does the request come from the booking JavaScript rather than the browser address bar?
     *
     * The portal posts JSON payloads without an `X-Requested-With` header, so the
     * content type is checked as well.
     *
     * @return bool
     */
    protected function wantsJsonResponse(): bool
    {
        if ($this->request->is('ajax') || $this->request->is('json')) {
            return true;
        }

        return str_contains($this->request->getHeaderLine('Content-Type'), 'application/json');
    }

    /**
     * Is an admin/staff user logged into the admin panel in this browser session?
     *
     * The admin panel uses the default `Auth` session key while patients use
     * `PatientAuth`, so the two never overlap.
     *
     * @return bool
     */
    protected function hasAdminSession(): bool
    {
        $authUser = $this->request->getSession()->read('Auth');
        if (!is_array($authUser) && !$authUser instanceof ArrayAccess) {
            return false;
        }

        return in_array($authUser['role'] ?? null, ['admin', 'staff'], true);
    }
}
