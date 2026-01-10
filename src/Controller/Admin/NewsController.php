<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\DateTime;
use Exception;
use Laminas\Diactoros\UploadedFile;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 * @method \App\Model\Entity\News[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsController extends AppController
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
        $query = $this->News->find()
            ->contain(['Staff', 'NewsCategories'])
            ->order(['News.created' => 'DESC']);

        // Filter by category if requested
        $categoryId = $this->request->getQuery('category_id');
        if ($categoryId) {
            $query->where(['News.category_id' => $categoryId]);
        }

        // Filter by author if requested
        $authorId = $this->request->getQuery('author_id');
        if ($authorId) {
            $query->where(['News.author_id' => $authorId]);
        }

        // Filter by published status
        $isPublished = $this->request->getQuery('is_published');
        if ($isPublished !== null && $isPublished !== '') {
            $query->where(['News.is_published' => (bool)$isPublished]);
        }

        // Search by title
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where(['News.title LIKE' => '%' . $search . '%']);
        }

        $news = $this->paginate($query);

        // Get categories for filter dropdown
        $categories = $this->News->NewsCategories->find('list', keyField: 'id', valueField: 'name')
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get authors for filter dropdown
        $authors = $this->News->Staff->find('list', keyField: 'id', valueField: function ($staff) {
            return $staff->first_name . ' ' . $staff->last_name;
        })
            ->order(['first_name' => 'ASC', 'last_name' => 'ASC'])
            ->toArray();

        $this->set(compact('news', 'categories', 'authors'));
    }

    /**
     * View method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $newsItem = $this->News->get($id, [
            'contain' => ['Staff', 'NewsCategories'],
        ]);

        $this->set(compact('newsItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $newsItem = $this->News->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Handle file upload for featured image
            if (!empty($data['featured_image_file']) && $data['featured_image_file']->getSize() > 0) {
                $uploadedFile = $data['featured_image_file'];
                $filename = $this->_uploadImage($uploadedFile);
                if ($filename) {
                    $data['featured_image'] = $filename;
                }
            }
            unset($data['featured_image_file']);

            // Set default values if not provided
            if (!isset($data['is_published'])) {
                $data['is_published'] = false;
            }
            if (!isset($data['views_count'])) {
                $data['views_count'] = 0;
            }

            // Set author to current user if available
            $user = $this->request->getAttribute('identity');
            if ($user && !isset($data['author_id'])) {
                // Try to find staff member with same email as user
                $staff = $this->News->Staff->find()
                    ->where(['email' => $user->email])
                    ->first();
                if ($staff) {
                    $data['author_id'] = $staff->id;
                }
            }

            // Set publish date if published
            if ($data['is_published'] && empty($data['publish_date'])) {
                $data['publish_date'] = new DateTime();
            }

            $newsItem = $this->News->patchEntity($newsItem, $data);

            if ($this->News->save($newsItem)) {
                $this->Flash->success(__('The news article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news article could not be saved. Please, try again.'));
        }

        // Get categories list for dropdown
        $categories = $this->News->NewsCategories->find('list', keyField: 'id', valueField: 'name')
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get authors list for dropdown
        $authors = $this->News->Staff->find('list', keyField: 'id', valueField: function ($staff) {
            return $staff->first_name . ' ' . $staff->last_name . ($staff->title ? ' (' . $staff->title . ')' : '');
        })
            ->order(['first_name' => 'ASC', 'last_name' => 'ASC'])
            ->toArray();

        $this->set(compact('newsItem', 'categories', 'authors'));
    }

    /**
     * Edit method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $newsItem = $this->News->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle file upload for featured image
            if (!empty($data['featured_image_file']) && $data['featured_image_file']->getSize() > 0) {
                $uploadedFile = $data['featured_image_file'];
                $filename = $this->_uploadImage($uploadedFile);
                if ($filename) {
                    // Delete old image if exists
                    if ($newsItem->featured_image) {
                        $this->_deleteImage($newsItem->featured_image);
                    }
                    $data['featured_image'] = $filename;
                }
            }
            unset($data['featured_image_file']);

            // Set publish date if published and not already set
            if ($data['is_published'] && empty($data['publish_date']) && !$newsItem->publish_date) {
                $data['publish_date'] = new DateTime();
            }

            $newsItem = $this->News->patchEntity($newsItem, $data);

            if ($this->News->save($newsItem)) {
                $this->Flash->success(__('The news article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news article could not be saved. Please, try again.'));
        }

        // Get categories list for dropdown
        $categories = $this->News->NewsCategories->find('list', keyField: 'id', valueField: 'name')
            ->order(['name' => 'ASC'])
            ->toArray();

        // Get authors list for dropdown
        $authors = $this->News->Staff->find('list', keyField: 'id', valueField: function ($staff) {
            return $staff->first_name . ' ' . $staff->last_name . ($staff->title ? ' (' . $staff->title . ')' : '');
        })
            ->order(['first_name' => 'ASC', 'last_name' => 'ASC'])
            ->toArray();

        $this->set(compact('newsItem', 'categories', 'authors'));
    }

    /**
     * Delete method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $newsItem = $this->News->get($id);

        if ($this->News->delete($newsItem)) {
            // Delete associated image file
            if ($newsItem->featured_image) {
                $this->_deleteImage($newsItem->featured_image);
            }
            $this->Flash->success(__('The news article has been deleted.'));
        } else {
            $this->Flash->error(__('The news article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle published status
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function togglePublished(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $newsItem = $this->News->get($id);

        $newsItem->is_published = !$newsItem->is_published;

        // Set publish date if publishing for the first time
        if ($newsItem->is_published && !$newsItem->publish_date) {
            $newsItem->publish_date = new DateTime();
        }

        if ($this->News->save($newsItem)) {
            $status = $newsItem->is_published ? 'published' : 'unpublished';
            $this->Flash->success(__('The news article has been {0}.', $status));
        } else {
            $this->Flash->error(__('Could not update news article status.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Upload image file
     *
     * @param \Laminas\Diactoros\UploadedFile $file Uploaded file data
     * @return string|false Filename on success, false on failure
     */
    private function _uploadImage(UploadedFile $file)
    {
        if (!$file || $file->getSize() === 0) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getClientMediaType(), $allowedTypes)) {
            $this->Flash->error(__('Invalid file type. Please upload a valid image file.'));

            return false;
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file->getSize() > $maxSize) {
            $this->Flash->error(__('File too large. Maximum size is 10MB.'));

            return false;
        }

        $uploadDir = WWW_ROOT . 'img' . DS . 'news' . DS;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = uniqid('news_') . '.' . strtolower($extension);
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
     * Delete image file
     *
     * @param string $filename
     * @return bool
     */
    private function _deleteImage(string $filename)
    {
        $filepath = WWW_ROOT . 'img' . DS . 'news' . DS . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }
}
