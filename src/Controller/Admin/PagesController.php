<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Utility\Text;

/**
 * Pages Controller
 *
 * @property \App\Model\Table\PagesTable $Pages
 * @method \App\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PagesController extends AppController
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
        $query = $this->Pages->find()
            ->order(['Pages.created' => 'DESC']);

        $pages = $this->paginate($query);

        $this->set(compact('pages'));
    }

    /**
     * View method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['PageComponents']
        ]);

        $this->set(compact('page'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $page = $this->Pages->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Auto-generate slug from title if not provided
            if (empty($data['slug']) && !empty($data['title'])) {
                $data['slug'] = Text::slug(strtolower($data['title']));
            }
            
            $page = $this->Pages->patchEntity($page, $data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The page has been saved.'));

                return $this->redirect(['action' => 'edit', $page->id]);
            }
            $this->Flash->error(__('The page could not be saved. Please, try again.'));
        }
        $this->set(compact('page'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $page = $this->Pages->get($id, [
            'contain' => ['PageComponents']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Auto-generate slug from title if not provided
            if (empty($data['slug']) && !empty($data['title'])) {
                $data['slug'] = Text::slug(strtolower($data['title']));
            }
            
            $page = $this->Pages->patchEntity($page, $data);
            if ($this->Pages->save($page)) {
                $this->Flash->success(__('The page has been saved.'));

                return $this->redirect(['action' => 'edit', $page->id]);
            }
            $this->Flash->error(__('The page could not be saved. Please, try again.'));
        }
        
        $this->set(compact('page'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $page = $this->Pages->get($id);
        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The page has been deleted.'));
        } else {
            $this->Flash->error(__('The page could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Add component method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null Redirects to edit page.
     */
    public function addComponent($id = null)
    {
        $this->request->allowMethod(['post']);
        $page = $this->Pages->get($id);
        
        $data = $this->request->getData();
        $data['page_id'] = $id;
        
        // Handle image upload if file is provided
        $uploadedFile = $this->request->getUploadedFile('image_file');
        if ($data['type'] === 'image' && $uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($uploadedFile);
            if ($uploadResult['success']) {
                $data['url'] = $uploadResult['url'];
                $data['image_type'] = 'upload';
            } else {
                $this->Flash->error($uploadResult['error']);
                return $this->redirect(['action' => 'edit', $id]);
            }
        } else {
            // Default to URL type
            $data['image_type'] = 'url';
        }
        
        // Set sort order to be last
        $lastComponent = $this->Pages->PageComponents->find()
            ->where(['page_id' => $id])
            ->order(['sort_order' => 'DESC'])
            ->first();
        $data['sort_order'] = ($lastComponent ? $lastComponent->sort_order : 0) + 1;
        
        $component = $this->Pages->PageComponents->newEntity($data);
        
        if ($this->Pages->PageComponents->save($component)) {
            $this->Flash->success(__('The component has been added.'));
        } else {
            $this->Flash->error(__('The component could not be added. Please, try again.'));
        }

        return $this->redirect(['action' => 'edit', $id]);
    }

    /**
     * Handle image upload
     *
     * @param \Psr\Http\Message\UploadedFileInterface $uploadedFile Upload file object
     * @return array Result array with success status and url/error
     */
    private function handleImageUpload($uploadedFile)
    {
        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'No file uploaded'];
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mimeType = $uploadedFile->getClientMediaType();
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'];
        }
        
        // Validate file size (max 5MB)
        if ($uploadedFile->getSize() > 5 * 1024 * 1024) {
            return ['success' => false, 'error' => 'File size too large. Maximum 5MB allowed.'];
        }
        
        // Generate unique filename
        $originalName = $uploadedFile->getClientFilename();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . $extension;
        $uploadPath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $filename;
        
        // Move uploaded file
        try {
            $uploadedFile->moveTo($uploadPath);
            return ['success' => true, 'url' => '/img/uploads/' . $filename];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to upload file: ' . $e->getMessage()];
        }
    }

    /**
     * Edit component method
     *
     * @param string|null $id Component id.
     * @return \Cake\Http\Response|null Redirects to edit page.
     */
    public function editComponent($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);
        $component = $this->Pages->PageComponents->get($id);
        
        $component = $this->Pages->PageComponents->patchEntity($component, $this->request->getData());
        
        if ($this->Pages->PageComponents->save($component)) {
            $this->Flash->success(__('The component has been updated.'));
        } else {
            $this->Flash->error(__('The component could not be updated. Please, try again.'));
        }

        return $this->redirect(['action' => 'edit', $component->page_id]);
    }

    /**
     * Delete component method
     *
     * @param string|null $id Component id.
     * @return \Cake\Http\Response|null Redirects to edit page.
     */
    public function deleteComponent($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $component = $this->Pages->PageComponents->get($id);
        $pageId = $component->page_id;
        
        // Delete uploaded image file if it exists
        if ($component->type === 'image' && $component->image_type === 'upload' && $component->url) {
            $filePath = WWW_ROOT . ltrim($component->url, '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        if ($this->Pages->PageComponents->delete($component)) {
            $this->Flash->success(__('The component has been deleted.'));
        } else {
            $this->Flash->error(__('The component could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'edit', $pageId]);
    }

    /**
     * Reorder components method
     *
     * @param string|null $id Page id.
     * @return \Cake\Http\Response|null JSON response.
     */
    public function reorderComponents($id = null)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        $componentIds = $this->request->getData('component_ids');
        
        if ($componentIds) {
            foreach ($componentIds as $index => $componentId) {
                $this->Pages->PageComponents->updateAll(
                    ['sort_order' => $index + 1],
                    ['id' => $componentId, 'page_id' => $id]
                );
            }
            
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }
        
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
    }
}
