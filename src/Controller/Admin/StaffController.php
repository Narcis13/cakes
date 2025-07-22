<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;
use Laminas\Diactoros\UploadedFile;

/**
 * Staff Controller
 *
 * @property \App\Model\Table\StaffTable $Staff
 * @method \App\Model\Entity\Staff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffController extends AppController
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
        $query = $this->Staff->find()
            ->contain(['Departments', 'Specializations'])
            ->order(['Staff.first_name' => 'ASC', 'Staff.last_name' => 'ASC']);

        // Filter by department if requested
        $departmentId = $this->request->getQuery('department_id');
        if ($departmentId) {
            $query->where(['Staff.department_id' => $departmentId]);
        }

        // Filter by staff type
        $staffType = $this->request->getQuery('staff_type');
        if ($staffType) {
            $query->where(['Staff.staff_type' => $staffType]);
        }

        // Filter by active status
        $isActive = $this->request->getQuery('is_active');
        if ($isActive !== null && $isActive !== '') {
            $query->where(['Staff.is_active' => (bool)$isActive]);
        }

        $staff = $this->paginate($query);

        // Get departments for filter dropdown
        $departments = $this->Staff->Departments->find('list', keyField: 'id', valueField: 'name')
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get unique staff types for filter
        $staffTypes = $this->Staff->find()
            ->select(['staff_type'])
            ->distinct(['staff_type'])
            ->order(['staff_type' => 'ASC'])
            ->all()
            ->map(function ($row) {
                return [$row->staff_type => ucfirst($row->staff_type)];
            })
            ->toArray();

        $this->set(compact('staff', 'departments', 'staffTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $staffMember = $this->Staff->get($id, [
            'contain' => ['Departments', 'Specializations', 'Appointments' => function ($q) {
                return $q->where(['Appointments.appointment_date >=' => date('Y-m-d')])
                    ->order(['Appointments.appointment_date' => 'ASC'])
                    ->limit(10);
            }],
        ]);

        $this->set(compact('staffMember'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $staffMember = $this->Staff->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Handle file upload for photo
            if (!empty($data['photo_file']) && $data['photo_file']->getSize() > 0) {
                $uploadedFile = $data['photo_file'];
                $filename = $this->_uploadPhoto($uploadedFile);
                if ($filename) {
                    $data['photo'] = $filename;
                }
            }
            unset($data['photo_file']);

            // Set default values if not provided
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }
            if (!isset($data['staff_type'])) {
                $data['staff_type'] = 'doctor';
            }

            $staffMember = $this->Staff->patchEntity($staffMember, $data);

            if ($this->Staff->save($staffMember)) {
                $this->Flash->success(__('The staff member has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff member could not be saved. Please, try again.'));
        }

        // Get departments list for dropdown
        $departments = $this->Staff->Departments->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get specializations list for dropdown
        $specializations = $this->Staff->Specializations->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        $this->set(compact('staffMember', 'departments', 'specializations'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $staffMember = $this->Staff->get($id, [
            'contain' => ['Specializations'],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle file upload for photo
            if (!empty($data['photo_file']) && $data['photo_file']->getSize() > 0) {
                $uploadedFile = $data['photo_file'];
                $filename = $this->_uploadPhoto($uploadedFile);
                if ($filename) {
                    // Delete old photo if exists
                    if ($staffMember->photo) {
                        $this->_deletePhoto($staffMember->photo);
                    }
                    $data['photo'] = $filename;
                }
            }
            unset($data['photo_file']);

            $staffMember = $this->Staff->patchEntity($staffMember, $data);

            if ($this->Staff->save($staffMember)) {
                $this->Flash->success(__('The staff member has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff member could not be saved. Please, try again.'));
        }

        // Get departments list for dropdown
        $departments = $this->Staff->Departments->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get specializations list for dropdown
        $specializations = $this->Staff->Specializations->find('list', keyField: 'id', valueField: 'name')
            ->where(['is_active' => true])
            ->order(['name' => 'ASC'])
            ->toArray();

        $this->set(compact('staffMember', 'departments', 'specializations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $staffMember = $this->Staff->get($id);

        // Check if staff member is set as head doctor in any department
        $isDepartmentHead = $this->Staff->Departments->find()
            ->where(['head_doctor_id' => $id])
            ->count();

        if ($isDepartmentHead > 0) {
            $this->Flash->error(__(
                'This staff member cannot be deleted as they are the head doctor of a department.',
            ));

            return $this->redirect(['action' => 'index']);
        }

        // Check if staff member has upcoming appointments
        $upcomingAppointments = $this->Staff->Appointments->find()
            ->where([
                'doctor_id' => $id,
                'appointment_date >=' => date('Y-m-d'),
                'status IN' => ['scheduled', 'confirmed'],
            ])
            ->count();

        if ($upcomingAppointments > 0) {
            $this->Flash->error(__(
                'This staff member cannot be deleted as they have {0} upcoming appointments.',
                $upcomingAppointments,
            ));

            return $this->redirect(['action' => 'index']);
        }

        if ($this->Staff->delete($staffMember)) {
            // Delete associated photo file
            if ($staffMember->photo) {
                $this->_deletePhoto($staffMember->photo);
            }
            $this->Flash->success(__('The staff member has been deleted.'));
        } else {
            $this->Flash->error(__('The staff member could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle active status
     *
     * @param string|null $id Staff id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function toggleActive(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $staffMember = $this->Staff->get($id);

        $staffMember->is_active = !$staffMember->is_active;

        if ($this->Staff->save($staffMember)) {
            $status = $staffMember->is_active ? 'activated' : 'deactivated';
            $this->Flash->success(__('The staff member has been {0}.', $status));
        } else {
            $this->Flash->error(__('Could not update staff member status.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Upload photo file
     *
     * @param \Laminas\Diactoros\UploadedFile $file Uploaded file data
     * @return string|false Filename on success, false on failure
     */
    private function _uploadPhoto(UploadedFile $file)
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

        $uploadDir = WWW_ROOT . 'img' . DS . 'staff' . DS;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = uniqid('staff_') . '.' . strtolower($extension);
        $filepath = $uploadDir . $filename;

        try {
            $file->moveTo($filepath);

            return $filename;
        } catch (Exception $e) {
            $this->Flash->error(__('Failed to upload file: {0}', $e->getMessage()));

            return false;
        }
    }

    /**
     * Delete photo file
     *
     * @param string $filename
     * @return bool
     */
    private function _deletePhoto(string $filename)
    {
        $filepath = WWW_ROOT . 'img' . DS . 'staff' . DS . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }
}
