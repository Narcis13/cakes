<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\I18n\DateTime;

/**
 * Patients Controller
 *
 * @property \App\Model\Table\PatientsTable $Patients
 */
class PatientsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Patients->find();

        // Filter by search (name, email, phone)
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Patients.full_name LIKE' => '%' . $search . '%',
                    'Patients.email LIKE' => '%' . $search . '%',
                    'Patients.phone LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status) {
            switch ($status) {
                case 'active':
                    $query->where(['Patients.is_active' => true]);
                    break;
                case 'inactive':
                    $query->where(['Patients.is_active' => false]);
                    break;
                case 'verified':
                    $query->where(['Patients.email_verified_at IS NOT' => null]);
                    break;
                case 'unverified':
                    $query->where(['Patients.email_verified_at IS' => null]);
                    break;
                case 'locked':
                    $query->where([
                        'Patients.locked_until IS NOT' => null,
                        'Patients.locked_until >' => DateTime::now(),
                    ]);
                    break;
            }
        }

        $query->orderBy(['Patients.created' => 'DESC']);

        $patients = $this->paginate($query);

        $this->set(compact('patients'));
    }

    /**
     * View method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $patient = $this->Patients->get($id, contain: [
            'Appointments' => [
                'Doctors',
                'Services',
            ],
        ]);

        // Sort and limit appointments
        $appointments = $this->Patients->Appointments->find()
            ->contain(['Doctors', 'Services'])
            ->where(['Appointments.patient_id' => $patient->id])
            ->orderBy(['Appointments.appointment_date' => 'DESC', 'Appointments.appointment_time' => 'DESC'])
            ->limit(20)
            ->all();

        $this->set(compact('patient', 'appointments'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $patient = $this->Patients->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $patient = $this->Patients->patchEntity($patient, $this->request->getData(), [
                'fields' => ['full_name', 'phone', 'orizont_extins_programare'],
                'validate' => 'admin',
            ]);

            if ($this->Patients->save($patient)) {
                $this->Flash->success(__('Datele pacientului au fost actualizate.'));

                return $this->redirect(['action' => 'view', $patient->id]);
            }
            $this->Flash->error(__('Datele nu au putut fi actualizate. Vă rugăm să încercați din nou.'));
        }

        $this->set(compact('patient'));
    }

    /**
     * Toggle active status
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function toggleActive(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $patient = $this->Patients->get($id);

        $patient->setAccess('is_active', true);
        $patient->is_active = !$patient->is_active;

        if ($this->Patients->save($patient)) {
            $state = $patient->is_active ? 'activat' : 'dezactivat';
            $this->Flash->success(__('Contul pacientului a fost {0}.', $state));
        } else {
            $this->Flash->error(__('Statusul contului nu a putut fi modificat.'));
        }

        return $this->redirect(['action' => 'view', $patient->id]);
    }

    /**
     * Unlock a locked account
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function unlockAccount(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $patient = $this->Patients->get($id);

        $patient->setAccess('failed_login_attempts', true);
        $patient->setAccess('locked_until', true);
        $patient->failed_login_attempts = 0;
        $patient->locked_until = null;

        if ($this->Patients->save($patient)) {
            $this->Flash->success(__('Contul pacientului a fost deblocat.'));
        } else {
            $this->Flash->error(__('Contul nu a putut fi deblocat.'));
        }

        return $this->redirect(['action' => 'view', $patient->id]);
    }

    /**
     * Toggle extended booking horizon
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects to view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function toggleOrizontExtins(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $patient = $this->Patients->get($id);

        $patient->setAccess('orizont_extins_programare', true);
        $patient->orizont_extins_programare = !$patient->orizont_extins_programare;

        if ($this->Patients->save($patient)) {
            $state = $patient->orizont_extins_programare ? 'activat' : 'dezactivat';
            $this->Flash->success(__('Orizontul extins de programare a fost {0}.', $state));
        } else {
            $this->Flash->error(__('Orizontul de programare nu a putut fi modificat.'));
        }

        return $this->redirect(['action' => 'view', $patient->id]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Patient id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $patient = $this->Patients->get($id);

        // Check for future pending/confirmed appointments
        $futureAppointments = $this->Patients->Appointments->find()
            ->where([
                'Appointments.patient_id' => $patient->id,
                'Appointments.appointment_date >=' => date('Y-m-d'),
                'Appointments.status IN' => ['pending', 'confirmed'],
            ])
            ->count();

        if ($futureAppointments > 0) {
            $msg = 'Pacientul nu poate fi șters deoarece are {0} programare/programări viitoare active.';
            $this->Flash->error(__(
                $msg . ' Anulați mai întâi programările.',
                $futureAppointments,
            ));

            return $this->redirect(['action' => 'view', $patient->id]);
        }

        if ($this->Patients->delete($patient)) {
            $this->Flash->success(__('Pacientul a fost șters.'));
        } else {
            $this->Flash->error(__('Pacientul nu a putut fi șters. Vă rugăm să încercați din nou.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
