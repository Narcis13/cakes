<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Specializations Controller
 *
 * @property \App\Model\Table\SpecializationsTable $Specializations
 */
class SpecializationsController extends AppController
{
    /**
     * Initialize method
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
        $query = $this->Specializations->find()
            ->contain(['Staff'])
            ->order(['Specializations.name' => 'ASC']);
        $specializations = $this->paginate($query);

        $this->set(compact('specializations'));
    }

    /**
     * View method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $specialization = $this->Specializations->get($id, contain: ['Staff' => ['Departments']]);
        $this->set(compact('specialization'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $specialization = $this->Specializations->newEmptyEntity();
        if ($this->request->is('post')) {
            $specialization = $this->Specializations->patchEntity($specialization, $this->request->getData());
            if ($this->Specializations->save($specialization)) {
                $this->Flash->success(__('The specialization has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The specialization could not be saved. Please, try again.'));
        }
        $this->set(compact('specialization'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $specialization = $this->Specializations->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $specialization = $this->Specializations->patchEntity($specialization, $this->request->getData());
            if ($this->Specializations->save($specialization)) {
                $this->Flash->success(__('The specialization has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The specialization could not be saved. Please, try again.'));
        }
        $this->set(compact('specialization'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Specialization id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $specialization = $this->Specializations->get($id);
        if ($this->Specializations->delete($specialization)) {
            $this->Flash->success(__('The specialization has been deleted.'));
        } else {
            $this->Flash->error(__('The specialization could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
