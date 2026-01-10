<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Gallery Controller
 *
 * @property \App\Model\Table\GalleryItemsTable $GalleryItems
 */
class GalleryController extends AppController
{
    /**
     * Initialization hook method.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
        $this->GalleryItems = $this->fetchTable('GalleryItems');
    }

    /**
     * Index method - Display all gallery items with drag & drop reordering
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $galleryItems = $this->GalleryItems->find()
            ->order(['sort_order' => 'ASC'])
            ->all();

        $this->set(compact('galleryItems'));
    }

    /**
     * Add method - Add new gallery item
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $galleryItem = $this->GalleryItems->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Set sort_order to be last
            $lastItem = $this->GalleryItems->find()
                ->order(['sort_order' => 'DESC'])
                ->first();
            $data['sort_order'] = ($lastItem ? $lastItem->sort_order : 0) + 1;

            $galleryItem = $this->GalleryItems->patchEntity($galleryItem, $data);

            if ($this->GalleryItems->save($galleryItem)) {
                $this->Flash->success(__('Imaginea a fost adaugata in galerie.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Imaginea nu a putut fi salvata. Incercati din nou.'));
        }

        $this->set(compact('galleryItem'));
    }

    /**
     * Edit method - Edit existing gallery item
     *
     * @param string|null $id Gallery item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $galleryItem = $this->GalleryItems->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $galleryItem = $this->GalleryItems->patchEntity($galleryItem, $this->request->getData());

            if ($this->GalleryItems->save($galleryItem)) {
                $this->Flash->success(__('Modificarile au fost salvate.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Modificarile nu au putut fi salvate. Incercati din nou.'));
        }

        $this->set(compact('galleryItem'));
    }

    /**
     * Delete method - Delete gallery item
     *
     * @param string|null $id Gallery item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $galleryItem = $this->GalleryItems->get($id);

        if ($this->GalleryItems->delete($galleryItem)) {
            $this->Flash->success(__('Imaginea a fost stearsa din galerie.'));
        } else {
            $this->Flash->error(__('Imaginea nu a putut fi stearsa. Incercati din nou.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Reorder method - AJAX endpoint for drag & drop reordering
     *
     * @return \Cake\Http\Response|null JSON response.
     */
    public function reorder()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $itemIds = $this->request->getData('item_ids');

        if ($itemIds) {
            foreach ($itemIds as $index => $itemId) {
                $this->GalleryItems->updateAll(
                    ['sort_order' => $index + 1],
                    ['id' => $itemId],
                );
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
    }

    /**
     * Toggle Active method - AJAX endpoint to toggle is_active status
     *
     * @param string|null $id Gallery item id.
     * @return \Cake\Http\Response|null JSON response.
     */
    public function toggleActive(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $galleryItem = $this->GalleryItems->get($id);
        $galleryItem->is_active = !$galleryItem->is_active;

        if ($this->GalleryItems->save($galleryItem)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'is_active' => $galleryItem->is_active,
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
    }

    /**
     * Browse Media method - Return JSON list of images from Media library
     *
     * @return \Cake\Http\Response JSON response.
     */
    public function browseMedia()
    {
        $this->autoRender = false;

        $files = [];

        // Scan /img/uploads/ directory (Media library)
        $uploadsDir = WWW_ROOT . 'img' . DS . 'uploads';
        if (is_dir($uploadsDir)) {
            $scannedFiles = array_diff(scandir($uploadsDir), ['.', '..']);
            foreach ($scannedFiles as $file) {
                $filePath = $uploadsDir . DS . $file;
                if (is_file($filePath)) {
                    $fileInfo = pathinfo($file);
                    $extension = strtolower($fileInfo['extension'] ?? '');
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                    if ($isImage) {
                        $files[] = [
                            'filename' => $file,
                            'title' => $fileInfo['filename'],
                            'url' => '/img/uploads/' . $file,
                            'size' => filesize($filePath),
                            'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                            'source' => 'uploads',
                        ];
                    }
                }
            }
        }

        // Scan /img/gallery/ directory (existing gallery images)
        $galleryDir = WWW_ROOT . 'img' . DS . 'gallery';
        if (is_dir($galleryDir)) {
            $scannedFiles = array_diff(scandir($galleryDir), ['.', '..']);
            foreach ($scannedFiles as $file) {
                $filePath = $galleryDir . DS . $file;
                if (is_file($filePath)) {
                    $fileInfo = pathinfo($file);
                    $extension = strtolower($fileInfo['extension'] ?? '');
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                    if ($isImage) {
                        $files[] = [
                            'filename' => $file,
                            'title' => $fileInfo['filename'],
                            'url' => '/img/gallery/' . $file,
                            'size' => filesize($filePath),
                            'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                            'source' => 'gallery',
                        ];
                    }
                }
            }
        }

        // Sort by date descending (newest first)
        usort($files, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'files' => $files,
            ]));
    }
}
