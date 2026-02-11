<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * NavbarItems Controller
 *
 * @property \App\Model\Table\NavbarItemsTable $NavbarItems
 */
class NavbarItemsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->NavbarItems->find()
            ->contain(['ParentNavbarItems'])
            ->orderBy(['NavbarItems.sort_order' => 'ASC']);
        $navbarItems = $this->paginate($query);

        $this->set(compact('navbarItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Navbar Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $navbarItem = $this->NavbarItems->get($id, [
            'contain' => ['ParentNavbarItems', 'ChildNavbarItems'],
        ]);

        $this->set(compact('navbarItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $navbarItem = $this->NavbarItems->newEmptyEntity();
        if ($this->request->is('post')) {
            $navbarItem = $this->NavbarItems->patchEntity($navbarItem, $this->request->getData());
            if ($this->NavbarItems->save($navbarItem)) {
                $this->Flash->success(__('The navbar item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The navbar item could not be saved. Please, try again.'));
        }
        $parentNavbarItems = $this->NavbarItems->ParentNavbarItems->find('list', ['limit' => 200]);
        $this->set(compact('navbarItem', 'parentNavbarItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Navbar Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $navbarItem = $this->NavbarItems->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $navbarItem = $this->NavbarItems->patchEntity($navbarItem, $this->request->getData());
            if ($this->NavbarItems->save($navbarItem)) {
                $this->Flash->success(__('The navbar item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The navbar item could not be saved. Please, try again.'));
        }
        $parentNavbarItems = $this->NavbarItems->ParentNavbarItems->find('list', ['limit' => 200]);
        $this->set(compact('navbarItem', 'parentNavbarItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Navbar Item id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $navbarItem = $this->NavbarItems->get($id);
        if ($this->NavbarItems->delete($navbarItem)) {
            $this->Flash->success(__('The navbar item has been deleted.'));
        } else {
            $this->Flash->error(__('The navbar item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
