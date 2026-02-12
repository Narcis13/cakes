<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\AppointmentEmailService;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Log\Log;
use Exception;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 */
class AppointmentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Appointments->find()
            ->contain(['Doctors', 'Services']);

        // Filter by date
        $date = $this->request->getQuery('date');
        if ($date) {
            $query->where(['Appointments.appointment_date' => $date]);
        }

        // Filter by date range
        $dateFrom = $this->request->getQuery('date_from');
        $dateTo = $this->request->getQuery('date_to');
        if ($dateFrom && $dateTo) {
            $query->where([
                'Appointments.appointment_date >=' => $dateFrom,
                'Appointments.appointment_date <=' => $dateTo,
            ]);
        }

        // Filter by doctor
        $doctorId = $this->request->getQuery('doctor_id');
        if ($doctorId) {
            $query->where(['Appointments.doctor_id' => $doctorId]);
        }

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status) {
            $query->where(['Appointments.status' => $status]);
        }

        // Filter by patient name
        $patientName = $this->request->getQuery('patient_name');
        if ($patientName) {
            $query->where(['Appointments.patient_name LIKE' => '%' . $patientName . '%']);
        }

        // Order by date and time
        $query->orderBy([
            'Appointments.appointment_date' => 'DESC',
            'Appointments.appointment_time' => 'DESC',
        ]);

        $appointments = $this->paginate($query);

        // Get lists for filters
        $doctors = $this->Appointments->Doctors->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ])->toArray();

        $statuses = [
            'pending' => 'În așteptare',
            'confirmed' => 'Confirmată',
            'cancelled' => 'Anulată',
            'completed' => 'Finalizată',
            'no-show' => 'Neprezentare',
        ];

        $this->set(compact('appointments', 'doctors', 'statuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Doctors', 'Services'],
        ]);

        $this->set(compact('appointment'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());

            // If changing appointment time, recalculate end time
            if ($appointment->isDirty('appointment_time') || $appointment->isDirty('service_id')) {
                $service = $this->Appointments->Services->get($appointment->service_id);
                $appointment->end_time = $appointment->appointment_time->addMinutes($service->duration_minutes);
            }

            if ($this->Appointments->save($appointment)) {
                $this->Flash->success(__('Programarea a fost actualizată.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Programarea nu a putut fi actualizată. Vă rugăm să încercați din nou.'));
        }

        $staff = $this->Appointments->Doctors->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ])->toArray();

        $services = $this->Appointments->Services->find('list', [
            'order' => ['name' => 'ASC'],
        ])->toArray();

        $statuses = [
            'pending' => 'În așteptare',
            'confirmed' => 'Confirmată',
            'cancelled' => 'Anulată',
            'completed' => 'Finalizată',
            'no-show' => 'Neprezentare',
        ];

        $this->set(compact('appointment', 'staff', 'services', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appointment = $this->Appointments->get($id);

        if ($this->Appointments->delete($appointment)) {
            $this->Flash->success(__('Programarea a fost ștearsă.'));
        } else {
            $this->Flash->error(__('Programarea nu a putut fi ștearsă. Vă rugăm să încercați din nou.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Cancel appointment
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function cancel(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Doctors' => ['Departments'], 'Services'],
        ]);

        $appointment->status = 'cancelled';
        $appointment->cancelled_at = Time::now();

        $reason = $this->request->getData('cancellation_reason');
        if ($reason) {
            $appointment->cancellation_reason = $reason;
        }

        if ($this->Appointments->save($appointment)) {
            $this->Flash->success(__('Programarea a fost anulată.'));

            // Send cancellation email to patient
            if ($appointment->patient_email) {
                try {
                    $emailService = new AppointmentEmailService();
                    $emailService->sendCancellation($appointment, $reason);
                } catch (Exception $e) {
                    Log::error('Failed to send cancellation email: ' . $e->getMessage());
                    $this->Flash->warning(__('Programarea a fost anulată, dar emailul de notificare nu a putut fi trimis.'));
                }
            }
        } else {
            $this->Flash->error(__('Programarea nu a putut fi anulată. Vă rugăm să încercați din nou.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Today's appointments
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function today()
    {
        $query = $this->Appointments->find()
            ->contain(['Doctors', 'Services'])
            ->where(['Appointments.appointment_date' => Date::now()])
            ->orderBy([
                'Appointments.appointment_time' => 'ASC',
            ]);

        $appointments = $this->paginate($query);

        // Get lists for filters (same as index action)
        $doctors = $this->Appointments->Doctors->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ])->toArray();

        $statuses = [
            'pending' => 'În așteptare',
            'confirmed' => 'Confirmată',
            'cancelled' => 'Anulată',
            'completed' => 'Finalizată',
            'no-show' => 'Neprezentare',
        ];

        $this->set(compact('appointments', 'doctors', 'statuses'));
        $this->render('index');
    }

    /**
     * Report method - generates a printable report
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function report()
    {
        $query = $this->Appointments->find()
            ->contain(['Doctors', 'Services']);

        $activeFilters = [];
        $statuses = [
            'pending' => 'În așteptare',
            'confirmed' => 'Confirmată',
            'cancelled' => 'Anulată',
            'completed' => 'Finalizată',
            'no-show' => 'Neprezentare',
        ];

        // Filter by date
        $date = $this->request->getQuery('date');
        if ($date) {
            $query->where(['Appointments.appointment_date' => $date]);
            $activeFilters['Data'] = Date::parse($date)->format('d.m.Y');
        }

        // Filter by date range
        $dateFrom = $this->request->getQuery('date_from');
        $dateTo = $this->request->getQuery('date_to');
        if ($dateFrom && $dateTo) {
            $query->where([
                'Appointments.appointment_date >=' => $dateFrom,
                'Appointments.appointment_date <=' => $dateTo,
            ]);
            $activeFilters['Perioada'] = Date::parse($dateFrom)->format('d.m.Y') . ' - ' . Date::parse($dateTo)->format('d.m.Y');
        } elseif ($dateFrom) {
            $query->where(['Appointments.appointment_date >=' => $dateFrom]);
            $activeFilters['De la data'] = Date::parse($dateFrom)->format('d.m.Y');
        } elseif ($dateTo) {
            $query->where(['Appointments.appointment_date <=' => $dateTo]);
            $activeFilters['Până la data'] = Date::parse($dateTo)->format('d.m.Y');
        }

        // Filter by doctor
        $doctorId = $this->request->getQuery('doctor_id');
        if ($doctorId) {
            $query->where(['Appointments.doctor_id' => $doctorId]);
            $doctor = $this->Appointments->Doctors->get($doctorId);
            $activeFilters['Medic'] = $doctor->first_name . ' ' . $doctor->last_name;
        }

        // Filter by status
        $status = $this->request->getQuery('status');
        if ($status) {
            $query->where(['Appointments.status' => $status]);
            $activeFilters['Status'] = $statuses[$status] ?? $status;
        }

        // Filter by patient name
        $patientName = $this->request->getQuery('patient_name');
        if ($patientName) {
            $query->where(['Appointments.patient_name LIKE' => '%' . $patientName . '%']);
            $activeFilters['Pacient'] = $patientName;
        }

        // Order by doctor, then date and time
        $query->orderBy([
            'Doctors.last_name' => 'ASC',
            'Doctors.first_name' => 'ASC',
            'Appointments.appointment_date' => 'DESC',
            'Appointments.appointment_time' => 'DESC',
        ]);

        // Get all results without pagination
        $appointments = $query->all();

        $this->set(compact('appointments', 'activeFilters', 'statuses'));
        $this->viewBuilder()->setLayout('print');
    }

    /**
     * Export appointments to CSV
     *
     * @return \Cake\Http\Response
     */
    public function export()
    {
        $query = $this->Appointments->find()
            ->contain(['Doctors', 'Services']);

        // Apply filters from query string
        $dateFrom = $this->request->getQuery('date_from');
        $dateTo = $this->request->getQuery('date_to');
        if ($dateFrom && $dateTo) {
            $query->where([
                'Appointments.appointment_date >=' => $dateFrom,
                'Appointments.appointment_date <=' => $dateTo,
            ]);
        }

        $doctorId = $this->request->getQuery('doctor_id');
        if ($doctorId) {
            $query->where(['Appointments.doctor_id' => $doctorId]);
        }

        $appointments = $query->toArray();

        $data = [];
        $data[] = ['Data', 'Ora', 'Pacient', 'Telefon', 'Email', 'Medic', 'Serviciu', 'Status', 'Observații'];

        foreach ($appointments as $appointment) {
            $data[] = [
                $appointment->appointment_date->format('Y-m-d'),
                $appointment->appointment_time->format('H:i'),
                $appointment->patient_name,
                $appointment->patient_phone,
                $appointment->patient_email,
                $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name,
                $appointment->service->name,
                $appointment->status,
                $appointment->notes,
            ];
        }

        $this->setResponse($this->response->withType('csv'));
        $this->set(compact('data'));
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'data',
            'header' => false,
        ]);
    }
}
