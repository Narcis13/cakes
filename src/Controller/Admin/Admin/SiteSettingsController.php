<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Admin/SiteSettings Controller
 *
 */
class Admin/SiteSettingsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Admin/SiteSettings->find();
        $admin/siteSettings = $this->paginate($query);

        $this->set(compact('admin/siteSettings'));
    }

    /**
     * View method
     *
     * @param string|null $id Admin/site Setting id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $admin/siteSetting = $this->Admin/SiteSettings->get($id, contain: []);
        $this->set(compact('admin/siteSetting'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $admin/siteSetting = $this->Admin/SiteSettings->newEmptyEntity();
        if ($this->request->is('post')) {
            $admin/siteSetting = $this->Admin/SiteSettings->patchEntity($admin/siteSetting, $this->request->getData());
            if ($this->Admin/SiteSettings->save($admin/siteSetting)) {
                $this->Flash->success(__('The admin/site setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin/site setting could not be saved. Please, try again.'));
        }
        $this->set(compact('admin/siteSetting'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin/site Setting id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $admin/siteSetting = $this->Admin/SiteSettings->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin/siteSetting = $this->Admin/SiteSettings->patchEntity($admin/siteSetting, $this->request->getData());
            if ($this->Admin/SiteSettings->save($admin/siteSetting)) {
                $this->Flash->success(__('The admin/site setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin/site setting could not be saved. Please, try again.'));
        }
        $this->set(compact('admin/siteSetting'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin/site Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $admin/siteSetting = $this->Admin/SiteSettings->get($id);
        if ($this->Admin/SiteSettings->delete($admin/siteSetting)) {
            $this->Flash->success(__('The admin/site setting has been deleted.'));
        } else {
            $this->Flash->error(__('The admin/site setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
