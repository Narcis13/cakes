<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\NotFoundException;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 * @method \App\Model\Entity\File[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesController extends AppController
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
        $query = $this->Files->find()
            ->contain(['Users'])
            ->order(['Files.created' => 'DESC']);

        // Filter by file type if specified
        if ($this->request->getQuery('type')) {
            $query = $query->where(['Files.file_type' => $this->request->getQuery('type')]);
        }

        // Filter by category if specified
        if ($this->request->getQuery('category')) {
            $query = $query->where(['Files.category' => $this->request->getQuery('category')]);
        }

        // Search functionality
        if ($this->request->getQuery('search')) {
            $search = $this->request->getQuery('search');
            $query = $query->where([
                'OR' => [
                    'Files.original_name LIKE' => "%{$search}%",
                    'Files.description LIKE' => "%{$search}%",
                    'Files.category LIKE' => "%{$search}%",
                ],
            ]);
        }

        $files = $this->paginate($query);

        // Get distinct file types and categories for filters
        $fileTypes = $this->Files->find()
            ->select(['file_type'])
            ->distinct(['file_type'])
            ->where(['file_type IS NOT' => null])
            ->orderBy(['file_type' => 'ASC'])
            ->toArray();

        $categories = $this->Files->find()
            ->select(['category'])
            ->distinct(['category'])
            ->where(['category IS NOT' => null])
            ->orderBy(['category' => 'ASC'])
            ->toArray();

        $this->set(compact('files', 'fileTypes', 'categories'));
    }

    /**
     * View method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('file'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $file = $this->Files->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $uploadedFile = $this->request->getUploadedFile('file');

            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleFileUpload($uploadedFile);

                if ($uploadResult['success']) {
                    $data['filename'] = $uploadResult['filename'];
                    $data['original_name'] = $uploadedFile->getClientFilename();
                    $data['file_path'] = $uploadResult['path'];
                    $data['file_url'] = $uploadResult['url'];
                    $data['mime_type'] = $uploadedFile->getClientMediaType();
                    $data['file_size'] = $uploadedFile->getSize();
                    $data['file_type'] = $this->getFileType($uploadedFile->getClientFilename());
                    $data['uploaded_by'] = $this->request->getAttribute('identity')->id ?? null;

                    $file = $this->Files->patchEntity($file, $data);

                    if ($this->Files->save($file)) {
                        $this->Flash->success(__('The file has been uploaded successfully.'));

                        return $this->redirect(['action' => 'index']);
                    }

                    // If save failed, clean up uploaded file
                    if (file_exists($uploadResult['path'])) {
                        unlink($uploadResult['path']);
                    }

                    $this->Flash->error(__('The file could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error($uploadResult['error']);
                }
            } else {
                $this->Flash->error(__('Please select a file to upload.'));
            }
        }

        $this->set(compact('file'));
    }

    /**
     * Edit method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle file replacement if new file is uploaded
            $uploadedFile = $this->request->getUploadedFile('file');
            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                // Delete old file
                if (file_exists($file->file_path)) {
                    unlink($file->file_path);
                }

                $uploadResult = $this->handleFileUpload($uploadedFile);

                if ($uploadResult['success']) {
                    $data['filename'] = $uploadResult['filename'];
                    $data['original_name'] = $uploadedFile->getClientFilename();
                    $data['file_path'] = $uploadResult['path'];
                    $data['file_url'] = $uploadResult['url'];
                    $data['mime_type'] = $uploadedFile->getClientMediaType();
                    $data['file_size'] = $uploadedFile->getSize();
                    $data['file_type'] = $this->getFileType($uploadedFile->getClientFilename());
                } else {
                    $this->Flash->error($uploadResult['error']);

                    return $this->redirect(['action' => 'edit', $id]);
                }
            }

            $file = $this->Files->patchEntity($file, $data);

            if ($this->Files->save($file)) {
                $this->Flash->success(__('The file has been updated.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The file could not be updated. Please, try again.'));
        }

        $this->set(compact('file'));
    }

    /**
     * Delete method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $file = $this->Files->get($id);

        // Delete physical file
        if (file_exists($file->file_path)) {
            unlink($file->file_path);
        }

        if ($this->Files->delete($file)) {
            $this->Flash->success(__('The file has been deleted.'));
        } else {
            $this->Flash->error(__('The file could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Download method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function download(?string $id = null)
    {
        $file = $this->Files->get($id);

        if (!file_exists($file->file_path)) {
            throw new NotFoundException(__('File not found on server.'));
        }

        // Increment download count
        $this->Files->updateAll(
            ['download_count' => $file->download_count + 1],
            ['id' => $file->id],
        );

        $this->response = $this->response->withFile($file->file_path, [
            'download' => true,
            'name' => $file->original_name,
        ]);

        return $this->response;
    }

    /**
     * Handle file upload
     *
     * @param \Psr\Http\Message\UploadedFileInterface $uploadedFile Upload file object
     * @return array Result array with success status and path/url/error
     */
    private function handleFileUpload(UploadedFileInterface $uploadedFile)
    {
        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'No file uploaded or upload error occurred'];
        }

        // Validate file type
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/zip',
            'application/x-rar-compressed',
        ];

        $mimeType = $uploadedFile->getClientMediaType();
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type. Only documents, images, and archives are allowed.'];
        }

        // Validate file size (max 10MB)
        if ($uploadedFile->getSize() > 10 * 1024 * 1024) {
            return ['success' => false, 'error' => 'File size too large. Maximum 10MB allowed.'];
        }

        // Create uploads directory if it doesn't exist
        $uploadsDir = WWW_ROOT . 'files' . DS . 'uploads';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        // Generate unique filename
        $originalName = $uploadedFile->getClientFilename();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = uniqid('file_') . '.' . $extension;
        $uploadPath = $uploadsDir . DS . $filename;

        // Move uploaded file
        try {
            $uploadedFile->moveTo($uploadPath);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $uploadPath,
                'url' => '/files/uploads/' . $filename,
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to upload file: ' . $e->getMessage()];
        }
    }

    /**
     * Get file type from filename
     *
     * @param string $filename
     * @return string
     */
    private function getFileType(string $filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $typeMap = [
            'pdf' => 'pdf',
            'doc' => 'document',
            'docx' => 'document',
            'xls' => 'spreadsheet',
            'xlsx' => 'spreadsheet',
            'ppt' => 'presentation',
            'pptx' => 'presentation',
            'txt' => 'text',
            'csv' => 'text',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'webp' => 'image',
            'zip' => 'archive',
            'rar' => 'archive',
        ];

        return $typeMap[$extension] ?? 'other';
    }
}
