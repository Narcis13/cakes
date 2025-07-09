<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * HospitalHolidays Controller
 *
 * @property \App\Model\Table\HospitalHolidaysTable $HospitalHolidays
 */
class HospitalHolidaysController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $year = $this->request->getQuery('year', date('Y'));
        
        $query = $this->HospitalHolidays->findByYear((int)$year);
        $hospitalHolidays = $this->paginate($query);
        
        // Get list of years that have holidays
        $years = $this->HospitalHolidays->find()
            ->select(['year' => 'YEAR(date)'])
            ->distinct(['year'])
            ->order(['year' => 'DESC'])
            ->toArray();
        
        $yearsList = [];
        foreach ($years as $y) {
            $yearsList[$y->year] = $y->year;
        }
        
        // Add current year if not in list
        if (!isset($yearsList[$year])) {
            $yearsList[$year] = $year;
            ksort($yearsList);
        }

        $this->set(compact('hospitalHolidays', 'year', 'yearsList'));
    }

    /**
     * View method
     *
     * @param string|null $id Hospital Holiday id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $hospitalHoliday = $this->HospitalHolidays->get($id);

        $this->set(compact('hospitalHoliday'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $hospitalHoliday = $this->HospitalHolidays->newEmptyEntity();
        if ($this->request->is('post')) {
            $hospitalHoliday = $this->HospitalHolidays->patchEntity($hospitalHoliday, $this->request->getData());
            if ($this->HospitalHolidays->save($hospitalHoliday)) {
                $this->Flash->success(__('The hospital holiday has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hospital holiday could not be saved. Please, try again.'));
        }
        $this->set(compact('hospitalHoliday'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Hospital Holiday id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $hospitalHoliday = $this->HospitalHolidays->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $hospitalHoliday = $this->HospitalHolidays->patchEntity($hospitalHoliday, $this->request->getData());
            if ($this->HospitalHolidays->save($hospitalHoliday)) {
                $this->Flash->success(__('The hospital holiday has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The hospital holiday could not be saved. Please, try again.'));
        }
        $this->set(compact('hospitalHoliday'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Hospital Holiday id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $hospitalHoliday = $this->HospitalHolidays->get($id);
        if ($this->HospitalHolidays->delete($hospitalHoliday)) {
            $this->Flash->success(__('The hospital holiday has been deleted.'));
        } else {
            $this->Flash->error(__('The hospital holiday could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}