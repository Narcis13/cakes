<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * ScheduleExceptions Controller
 *
 * @property \App\Model\Table\ScheduleExceptionsTable $ScheduleExceptions
 */
class ScheduleExceptionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ScheduleExceptions->find()
            ->contain(['Staff']);

        // Filter by staff
        $staffId = $this->request->getQuery('staff_id');
        if ($staffId) {
            $query->where(['ScheduleExceptions.staff_id' => $staffId]);
        }

        // Filter by date range
        $dateFrom = $this->request->getQuery('date_from');
        $dateTo = $this->request->getQuery('date_to');
        if ($dateFrom && $dateTo) {
            $query->where([
                'ScheduleExceptions.exception_date >=' => $dateFrom,
                'ScheduleExceptions.exception_date <=' => $dateTo
            ]);
        }

        // Filter by type (working/not working)
        $isWorking = $this->request->getQuery('is_working');
        if ($isWorking !== null && $isWorking !== '') {
            $query->where(['ScheduleExceptions.is_working' => (bool)$isWorking]);
        }

        $query->orderBy([
            'ScheduleExceptions.exception_date' => 'DESC',
            'Staff.first_name' => 'ASC',
            'Staff.last_name' => 'ASC'
        ]);

        $scheduleExceptions = $this->paginate($query);

        // Get staff list for filter
        $staff = $this->ScheduleExceptions->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC']
        ])->toArray();

        $this->set(compact('scheduleExceptions', 'staff'));
    }

    /**
     * View method
     *
     * @param string|null $id Schedule Exception id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scheduleException = $this->ScheduleExceptions->get($id, [
            'contain' => ['Staff'],
        ]);

        $this->set(compact('scheduleException'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scheduleException = $this->ScheduleExceptions->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $scheduleException = $this->ScheduleExceptions->patchEntity($scheduleException, $this->request->getData());
            
            if ($this->ScheduleExceptions->save($scheduleException)) {
                $this->Flash->success(__('Excepția a fost salvată.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Excepția nu a putut fi salvată. Vă rugăm să încercați din nou.'));
        }
        
        $staff = $this->ScheduleExceptions->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC']
        ])->toArray();
        
        $this->set(compact('scheduleException', 'staff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule Exception id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scheduleException = $this->ScheduleExceptions->get($id, [
            'contain' => [],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scheduleException = $this->ScheduleExceptions->patchEntity($scheduleException, $this->request->getData());
            
            if ($this->ScheduleExceptions->save($scheduleException)) {
                $this->Flash->success(__('Excepția a fost actualizată.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Excepția nu a putut fi actualizată. Vă rugăm să încercați din nou.'));
        }
        
        $staff = $this->ScheduleExceptions->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC']
        ])->toArray();
        
        $this->set(compact('scheduleException', 'staff'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule Exception id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scheduleException = $this->ScheduleExceptions->get($id);
        
        if ($this->ScheduleExceptions->delete($scheduleException)) {
            $this->Flash->success(__('Excepția a fost ștearsă.'));
        } else {
            $this->Flash->error(__('Excepția nu a putut fi ștearsă. Vă rugăm să încercați din nou.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Bulk add method - Add exceptions for multiple dates
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function bulkAdd()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $staffId = $data['staff_id'];
            $dateFrom = $data['date_from'];
            $dateTo = $data['date_to'];
            $isWorking = $data['is_working'];
            $startTime = $data['start_time'] ?? null;
            $endTime = $data['end_time'] ?? null;
            $reason = $data['reason'] ?? null;
            $skipWeekends = $data['skip_weekends'] ?? false;
            
            $saved = 0;
            $errors = 0;
            
            $currentDate = new \DateTime($dateFrom);
            $endDate = new \DateTime($dateTo);
            
            while ($currentDate <= $endDate) {
                // Skip weekends if requested
                if ($skipWeekends && in_array($currentDate->format('w'), [0, 6])) {
                    $currentDate->modify('+1 day');
                    continue;
                }
                
                // Check if exception already exists
                $existing = $this->ScheduleExceptions->find()
                    ->where([
                        'staff_id' => $staffId,
                        'exception_date' => $currentDate->format('Y-m-d')
                    ])
                    ->first();
                
                if (!$existing) {
                    $exception = $this->ScheduleExceptions->newEntity([
                        'staff_id' => $staffId,
                        'exception_date' => $currentDate->format('Y-m-d'),
                        'is_working' => $isWorking,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'reason' => $reason
                    ]);
                    
                    if ($this->ScheduleExceptions->save($exception)) {
                        $saved++;
                    } else {
                        $errors++;
                    }
                }
                
                $currentDate->modify('+1 day');
            }
            
            if ($saved > 0) {
                $this->Flash->success(__('Au fost create {0} excepții.', $saved));
            }
            if ($errors > 0) {
                $this->Flash->error(__('Nu s-au putut crea {0} excepții.', $errors));
            }
            
            return $this->redirect(['action' => 'index']);
        }
        
        $staff = $this->ScheduleExceptions->Staff->find('list', [
            'order' => ['first_name' => 'ASC', 'last_name' => 'ASC']
        ])->toArray();
        
        $this->set(compact('staff'));
    }
}