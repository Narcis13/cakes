<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\I18n\Time;
use Exception;

/**
 * DoctorSchedules Controller
 *
 * @property \App\Model\Table\DoctorSchedulesTable $DoctorSchedules
 */
class DoctorSchedulesController extends AppController
{
    /**
     * Index method - List all schedules with filtering
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->DoctorSchedules->find()
            ->contain(['Staff', 'Services']);

        // Filter by doctor
        $staffId = $this->request->getQuery('staff_id');
        if ($staffId) {
            $query->where(['DoctorSchedules.staff_id' => $staffId]);
        }

        // Filter by day of week
        $dayOfWeek = $this->request->getQuery('day_of_week');
        if ($dayOfWeek) {
            $query->where(['DoctorSchedules.day_of_week' => $dayOfWeek]);
        }

        // Filter by active status
        $isActive = $this->request->getQuery('is_active');
        if ($isActive !== null && $isActive !== '') {
            $query->where(['DoctorSchedules.is_active' => (bool)$isActive]);
        }

        // Filter by service
        $serviceId = $this->request->getQuery('service_id');
        if ($serviceId) {
            $query->where(['DoctorSchedules.service_id' => $serviceId]);
        }

        $query->orderBy([
            'Staff.first_name' => 'ASC',
            'Staff.last_name' => 'ASC',
            'DoctorSchedules.day_of_week' => 'ASC',
            'DoctorSchedules.start_time' => 'ASC',
        ]);

        $doctorSchedules = $this->paginate($query);

        // Get lists for filters
        $staff = $this->DoctorSchedules->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ])->toArray();

        $services = $this->DoctorSchedules->Services->find('list', [
            'order' => ['name' => 'ASC'],
        ])->toArray();

        $daysOfWeek = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        $this->set(compact('doctorSchedules', 'staff', 'services', 'daysOfWeek'));
    }

    /**
     * View method
     *
     * @param string|null $id Doctor Schedule id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $doctorSchedule = $this->DoctorSchedules->get($id, [
            'contain' => ['Staff', 'Services'],
        ]);

        $this->set(compact('doctorSchedule'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $doctorSchedule = $this->DoctorSchedules->newEmptyEntity();

        if ($this->request->is('post')) {
            $doctorSchedule = $this->DoctorSchedules->patchEntity($doctorSchedule, $this->request->getData());

            if ($this->DoctorSchedules->save($doctorSchedule)) {
                $this->Flash->success(__('The doctor schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The doctor schedule could not be saved. Please, try again.'));
        }

        $staff = $this->DoctorSchedules->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ]);

        $services = $this->DoctorSchedules->Services->find('list', [
            'order' => ['name' => 'ASC'],
        ]);

        $daysOfWeek = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        $this->set(compact('doctorSchedule', 'staff', 'services', 'daysOfWeek'));
    }

    /**
     * Bulk add method - Create multiple schedules at once
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function bulkAdd()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $errors = [];
            $savedCount = 0;

            // Begin transaction
            $connection = $this->DoctorSchedules->getConnection();
            $connection->begin();

            try {
                // Handle multiple staff members
                $staffIds = (array)($data['staff_ids'] ?? []);
                $daysOfWeek = (array)($data['days_of_week'] ?? []);

                foreach ($staffIds as $staffId) {
                    foreach ($daysOfWeek as $dayOfWeek) {
                        $scheduleData = [
                            'staff_id' => $staffId,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => $data['start_time'],
                            'end_time' => $data['end_time'],
                            'service_id' => $data['service_id'],
                            'max_appointments' => $data['max_appointments'] ?? 1,
                            'slot_duration' => $data['slot_duration'] ?? null,
                            'buffer_minutes' => $data['buffer_minutes'] ?? 0,
                            'is_active' => $data['is_active'] ?? true,
                        ];

                        $schedule = $this->DoctorSchedules->newEntity($scheduleData);

                        if ($this->DoctorSchedules->save($schedule)) {
                            $savedCount++;
                        } else {
                            $staff = $this->DoctorSchedules->Staff->get($staffId);
                            $errors[] = sprintf(
                                __('Failed to create schedule for %s on %s: %s'),
                                $staff->name,
                                $this->getDayName((int)$dayOfWeek),
                                $this->formatErrors($schedule->getErrors()),
                            );
                        }
                    }
                }

                if (empty($errors)) {
                    $connection->commit();
                    $this->Flash->success(
                        __n(
                            '{0} schedule has been created.',
                            '{0} schedules have been created.',
                            $savedCount,
                            $savedCount,
                        ),
                    );

                    return $this->redirect(['action' => 'index']);
                } else {
                    $connection->rollback();
                    foreach ($errors as $error) {
                        $this->Flash->error($error);
                    }
                }
            } catch (Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('An error occurred while creating schedules: {0}', $e->getMessage()));
            }
        }

        $staff = $this->DoctorSchedules->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ]);

        $services = $this->DoctorSchedules->Services->find('list', [
            'order' => ['name' => 'ASC'],
        ]);

        $daysOfWeek = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        $this->set(compact('staff', 'services', 'daysOfWeek'));
    }

    /**
     * Copy schedule method - Copy schedule from one doctor to another
     *
     * @return \Cake\Http\Response|null|void Redirects on successful copy, renders view otherwise.
     */
    public function copySchedule()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $sourceStaffId = $data['source_staff_id'] ?? null;
            $targetStaffIds = (array)($data['target_staff_ids'] ?? []);
            $adjustTime = (int)($data['adjust_minutes'] ?? 0);

            if (!$sourceStaffId || empty($targetStaffIds)) {
                $this->Flash->error(__('Please select source and target doctors.'));
            } else {
                $errors = [];
                $copiedCount = 0;

                // Get source schedules
                $sourceSchedules = $this->DoctorSchedules->find()
                    ->where([
                        'staff_id' => $sourceStaffId,
                        'is_active' => true,
                    ])
                    ->toArray();

                if (empty($sourceSchedules)) {
                    $this->Flash->error(__('The source doctor has no active schedules to copy.'));
                } else {
                    $connection = $this->DoctorSchedules->getConnection();
                    $connection->begin();

                    try {
                        foreach ($targetStaffIds as $targetStaffId) {
                            foreach ($sourceSchedules as $sourceSchedule) {
                                $newScheduleData = [
                                    'staff_id' => $targetStaffId,
                                    'day_of_week' => $sourceSchedule->day_of_week,
                                    'start_time' => $this->adjustTime($sourceSchedule->start_time, $adjustTime),
                                    'end_time' => $this->adjustTime($sourceSchedule->end_time, $adjustTime),
                                    'service_id' => $sourceSchedule->service_id,
                                    'max_appointments' => $sourceSchedule->max_appointments,
                                    'slot_duration' => $sourceSchedule->slot_duration,
                                    'buffer_minutes' => $sourceSchedule->buffer_minutes,
                                    'is_active' => true,
                                ];

                                $newSchedule = $this->DoctorSchedules->newEntity($newScheduleData);

                                if ($this->DoctorSchedules->save($newSchedule)) {
                                    $copiedCount++;
                                } else {
                                    $targetStaff = $this->DoctorSchedules->Staff->get($targetStaffId);
                                    $errors[] = sprintf(
                                        __('Failed to copy schedule for %s on %s: %s'),
                                        $targetStaff->name,
                                        $this->getDayName($sourceSchedule->day_of_week),
                                        $this->formatErrors($newSchedule->getErrors()),
                                    );
                                }
                            }
                        }

                        if (empty($errors)) {
                            $connection->commit();
                            $this->Flash->success(
                                __n(
                                    '{0} schedule has been copied.',
                                    '{0} schedules have been copied.',
                                    $copiedCount,
                                    $copiedCount,
                                ),
                            );

                            return $this->redirect(['action' => 'index']);
                        } else {
                            $connection->rollback();
                            foreach ($errors as $error) {
                                $this->Flash->error($error);
                            }
                        }
                    } catch (Exception $e) {
                        $connection->rollback();
                        $this->Flash->error(__('An error occurred while copying schedules: {0}', $e->getMessage()));
                    }
                }
            }
        }

        $staff = $this->DoctorSchedules->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ]);

        $this->set(compact('staff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Doctor Schedule id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $doctorSchedule = $this->DoctorSchedules->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $doctorSchedule = $this->DoctorSchedules->patchEntity($doctorSchedule, $this->request->getData());

            if ($this->DoctorSchedules->save($doctorSchedule)) {
                $this->Flash->success(__('The doctor schedule has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The doctor schedule could not be saved. Please, try again.'));
        }

        $staff = $this->DoctorSchedules->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC'],
        ]);

        $services = $this->DoctorSchedules->Services->find('list', [
            'order' => ['name' => 'ASC'],
        ]);

        $daysOfWeek = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        $this->set(compact('doctorSchedule', 'staff', 'services', 'daysOfWeek'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Doctor Schedule id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $doctorSchedule = $this->DoctorSchedules->get($id);

        if ($this->DoctorSchedules->delete($doctorSchedule)) {
            $this->Flash->success(__('The doctor schedule has been deleted.'));
        } else {
            $this->Flash->error(__('The doctor schedule could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Calendar view method - Weekly calendar view of all schedules
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function calendar()
    {
        // Get all active schedules grouped by day and doctor
        $schedules = $this->DoctorSchedules->find()
            ->where(['DoctorSchedules.is_active' => true])
            ->contain(['Staff', 'Services'])
            ->orderBy([
                'DoctorSchedules.day_of_week' => 'ASC',
                'DoctorSchedules.start_time' => 'ASC',
            ])
            ->toArray();

        // Group schedules by day of week
        $schedulesByDay = [];
        foreach ($schedules as $schedule) {
            $schedulesByDay[$schedule->day_of_week][] = $schedule;
        }

        $daysOfWeek = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        $this->set(compact('schedulesByDay', 'daysOfWeek'));
    }

    /**
     * Toggle active status via AJAX
     *
     * @param string|null $id Doctor Schedule id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function toggle(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $doctorSchedule = $this->DoctorSchedules->get($id);

        $doctorSchedule->is_active = !$doctorSchedule->is_active;

        if ($this->DoctorSchedules->save($doctorSchedule)) {
            $message = $doctorSchedule->is_active
                ? __('The schedule has been activated.')
                : __('The schedule has been deactivated.');
            $success = true;
        } else {
            $message = __('The schedule status could not be changed.');
            $success = false;
        }

        if ($this->request->is('ajax')) {
            $this->set([
                'success' => $success,
                'message' => $message,
                'is_active' => $doctorSchedule->is_active,
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'is_active']);
        } else {
            if ($success) {
                $this->Flash->success($message);
            } else {
                $this->Flash->error($message);
            }

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Helper method to get day name
     *
     * @param int $dayOfWeek Day number (1-7)
     * @return string
     */
    private function getDayName(int $dayOfWeek): string
    {
        $days = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        return $days[$dayOfWeek] ?? '';
    }

    /**
     * Helper method to format validation errors
     *
     * @param array $errors Validation errors
     * @return string
     */
    private function formatErrors(array $errors): string
    {
        $messages = [];
        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $messages[] = $error;
            }
        }

        return implode(', ', $messages);
    }

    /**
     * Helper method to adjust time by minutes
     *
     * @param \Cake\I18n\Time $time Original time
     * @param int $minutes Minutes to adjust (positive or negative)
     * @return \Cake\I18n\Time
     */
    private function adjustTime(Time $time, int $minutes)
    {
        if ($minutes === 0) {
            return $time;
        }

        $newTime = clone $time;
        if ($minutes > 0) {
            return $newTime->addMinute($minutes);
        } else {
            return $newTime->subMinute(abs($minutes));
        }
    }
}
