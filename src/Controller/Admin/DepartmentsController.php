<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 * @method \App\Model\Entity\Department[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepartmentsController extends AppController
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
        $query = $this->Departments->find()
            ->contain(['HeadDoctors'])
            ->order(['Departments.name' => 'ASC']);

        $departments = $this->paginate($query);

        $this->set(compact('departments'));
    }

    /**
     * View method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => ['HeadDoctors', 'Services', 'Staff']
        ]);

        $this->set(compact('department'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $department = $this->Departments->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Handle file upload for picture
            if (!empty($data['picture_file']) && $data['picture_file']->getSize() > 0) {
                $uploadedFile = $data['picture_file'];
                $filename = $this->_uploadPicture($uploadedFile);
                if ($filename) {
                    $data['picture'] = $filename;
                }
            }
            unset($data['picture_file']);
            
            $department = $this->Departments->patchEntity($department, $data);
            
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }

        // Get staff list for head doctor dropdown
        $staff = $this->Departments->HeadDoctors->find('list', [
            'keyField' => 'id',
            'valueField' => function ($staff) {
                return $staff->first_name . ' ' . $staff->last_name . ' (' . ($staff->title ?: $staff->specialization ?: 'Doctor') . ')';
            }
        ])->where(['is_active' => true])->toArray();

        $this->set(compact('department', 'staff'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => []
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Handle file upload for picture
            if (!empty($data['picture_file']) && $data['picture_file']->getSize() > 0) {
                $uploadedFile = $data['picture_file'];
                $filename = $this->_uploadPicture($uploadedFile);
                if ($filename) {
                    // Delete old picture if exists
                    if ($department->picture) {
                        $this->_deletePicture($department->picture);
                    }
                    $data['picture'] = $filename;
                }
            }
            unset($data['picture_file']);
            
            $department = $this->Departments->patchEntity($department, $data);
            
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }

        // Get staff list for head doctor dropdown
        $staff = $this->Departments->HeadDoctors->find('list', [
            'keyField' => 'id',
            'valueField' => function ($staff) {
                return $staff->first_name . ' ' . $staff->last_name . ' (' . ($staff->title ?: $staff->specialization ?: 'Doctor') . ')';
            }
        ])->where(['is_active' => true])->toArray();

        $this->set(compact('department', 'staff'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $department = $this->Departments->get($id);
        
        if ($this->Departments->delete($department)) {
            // Delete associated picture file
            if ($department->picture) {
                $this->_deletePicture($department->picture);
            }
            $this->Flash->success(__('The department has been deleted.'));
        } else {
            $this->Flash->error(__('The department could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Upload picture file
     *
     * @param \Laminas\Diactoros\UploadedFile $file Uploaded file data
     * @return string|false Filename on success, false on failure
     */
    private function _uploadPicture($file)
    {
        if (!$file || $file->getSize() === 0) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getClientMediaType(), $allowedTypes)) {
            $this->Flash->error(__('Invalid file type. Please upload a valid image file.'));
            return false;
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            $this->Flash->error(__('File too large. Maximum size is 5MB.'));
            return false;
        }

        $uploadDir = WWW_ROOT . 'img' . DS . 'departments' . DS;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = uniqid('dept_') . '.' . strtolower($extension);
        $filepath = $uploadDir . $filename;

        try {
            $file->moveTo($filepath);
            return $filename;
        } catch (\Exception $e) {
            $this->Flash->error(__('Failed to upload file: {0}', $e->getMessage()));
            return false;
        }
    }

    /**
     * Delete picture file
     *
     * @param string $filename
     * @return bool
     */
    private function _deletePicture($filename)
    {
        $filepath = WWW_ROOT . 'img' . DS . 'departments' . DS . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
