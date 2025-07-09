<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * StaffUnavailabilities Controller
 *
 * @property \App\Model\Table\StaffUnavailabilitiesTable $StaffUnavailabilities
 */
class StaffUnavailabilitiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->StaffUnavailabilities->find()
            ->contain(['Staff']);

        $staffUnavailabilities = $this->paginate($query);

        $this->set(compact('staffUnavailabilities'));
    }

    /**
     * View method
     *
     * @param string|null $id Staff Unavailability id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $staffUnavailability = $this->StaffUnavailabilities->get($id, contain: ['Staff']);

        $this->set(compact('staffUnavailability'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $staffUnavailability = $this->StaffUnavailabilities->newEmptyEntity();
        if ($this->request->is('post')) {
            $staffUnavailability = $this->StaffUnavailabilities->patchEntity($staffUnavailability, $this->request->getData());
            if ($this->StaffUnavailabilities->save($staffUnavailability)) {
                $this->Flash->success(__('The staff unavailability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff unavailability could not be saved. Please, try again.'));
        }
        $staff = $this->StaffUnavailabilities->Staff->find('list', [
            'order' => ['Staff.first_name' => 'ASC', 'Staff.last_name' => 'ASC']
        ]);
        $this->set(compact('staffUnavailability', 'staff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Staff Unavailability id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $staffUnavailability = $this->StaffUnavailabilities->get($id, contain: ['Staff']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $staffUnavailability = $this->StaffUnavailabilities->patchEntity($staffUnavailability, $this->request->getData());
            if ($this->StaffUnavailabilities->save($staffUnavailability)) {
                $this->Flash->success(__('The staff unavailability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff unavailability could not be saved. Please, try again.'));
        }
        $staff = $this->StaffUnavailabilities->Staff->find('list', [
            'order' => ['Staff.first_name' => 'ASC', 'Staff.last_name' => 'ASC']
        ]);
        $this->set(compact('staffUnavailability', 'staff'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Staff Unavailability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $staffUnavailability = $this->StaffUnavailabilities->get($id);
        if ($this->StaffUnavailabilities->delete($staffUnavailability)) {
            $this->Flash->success(__('The staff unavailability has been deleted.'));
        } else {
            $this->Flash->error(__('The staff unavailability could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}