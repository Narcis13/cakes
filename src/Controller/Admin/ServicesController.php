<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{
    /**
     * Initialization hook method.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Services->find()
            ->contain(['Departments'])
            ->order(['Services.name' => 'ASC']);

        // Filter by department if requested
        $departmentId = $this->request->getQuery('department_id');
        if ($departmentId) {
            $query->where(['Services.department_id' => $departmentId]);
        }

        // Filter by active status
        $isActive = $this->request->getQuery('is_active');
        if ($isActive !== null && $isActive !== '') {
            $query->where(['Services.is_active' => (bool)$isActive]);
        }

        $services = $this->paginate($query);

        // Get departments for filter dropdown
        $departments = $this->Services->Departments->find('list', keyField: 'id', valueField: 'name')
            ->order(['name' => 'ASC'])
            ->toArray();

        $this->set(compact('services', 'departments'));
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Departments', 'Appointments' => [
                'conditions' => ['Appointments.appointment_date >=' => date('Y-m-d')],
                'order' => ['Appointments.appointment_date' => 'ASC'],
                'limit' => 10,
            ]],
        ]);

        $this->set(compact('service'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $service = $this->Services->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Set default values if not provided
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }

            $service = $this->Services->patchEntity($service, $data);

            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        // Get departments list for dropdown
        $departments = $this->Services->Departments->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        $this->set(compact('service', 'departments'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $service = $this->Services->patchEntity($service, $data);

            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        // Get departments list for dropdown
        $departments = $this->Services->Departments->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        $this->set(compact('service', 'departments'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $service = $this->Services->get($id);

        // Check if service has upcoming appointments
        $upcomingAppointments = $this->Services->Appointments->find()
            ->where([
                'service_id' => $id,
                'appointment_date >=' => date('Y-m-d'),
                'status IN' => ['scheduled', 'confirmed'],
            ])
            ->count();

        if ($upcomingAppointments > 0) {
            $this->Flash->error(__(
                'This service cannot be deleted as it has {0} upcoming appointments.',
                $upcomingAppointments,
            ));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle active status
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function toggleActive(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $service = $this->Services->get($id);

        $service->is_active = !$service->is_active;

        if ($this->Services->save($service)) {
            $status = $service->is_active ? 'activated' : 'deactivated';
            $this->Flash->success(__('The service has been {0}.', $status));
        } else {
            $this->Flash->error(__('Could not update service status.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
