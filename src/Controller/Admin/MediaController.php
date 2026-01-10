<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Media Controller
 *
 * Media management for uploaded files and assets
 */
class MediaController extends AppController
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
     * Index method - Display media library
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Media Library');
    }

    /**
     * Upload method - Handle file uploads
     *
     * @return \Cake\Http\Response JSON response
     */
    public function upload()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $uploadedFiles = $this->request->getUploadedFiles();
        $results = [];

        if (empty($uploadedFiles['files'])) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'No files were uploaded',
                ]));
        }

        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $results[] = [
                    'success' => false,
                    'filename' => $uploadedFile->getClientFilename(),
                    'error' => 'Upload error: ' . $uploadedFile->getError(),
                ];
                continue;
            }

            $uploadResult = $this->handleFileUpload($uploadedFile);
            $results[] = $uploadResult;
        }

        $successCount = count(array_filter($results, fn($r) => $r['success']));
        $totalCount = count($results);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => $successCount > 0,
                'message' => "{$successCount} of {$totalCount} files uploaded successfully",
                'results' => $results,
            ]));
    }

    /**
     * Delete method - Delete uploaded file
     *
     * @return \Cake\Http\Response JSON response
     */
    public function deleteFile()
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->autoRender = false;

        $filename = $this->request->getData('filename');

        if (empty($filename)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'No filename provided',
                ]));
        }

        // Security check - prevent directory traversal
        $cleanFilename = basename($filename);
        $filePath = WWW_ROOT . 'img' . DS . 'uploads' . DS . $cleanFilename;

        if (!file_exists($filePath)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'File not found',
                ]));
        }

        if (unlink($filePath)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'File deleted successfully',
                ]));
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Failed to delete file',
                ]));
        }
    }

    /**
     * Handle file upload
     *
     * @param \Psr\Http\Message\UploadedFileInterface $uploadedFile Upload file object
     * @return array Result array with success status and filename/error
     */
    private function handleFileUpload(UploadedFileInterface $uploadedFile)
    {
        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'filename' => $uploadedFile ? $uploadedFile->getClientFilename() : 'Unknown',
                'error' => 'No file uploaded',
            ];
        }

        // Validate file type
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $mimeType = $uploadedFile->getClientMediaType();

        if (!in_array($mimeType, $allowedTypes)) {
            return [
                'success' => false,
                'filename' => $uploadedFile->getClientFilename(),
                'error' => 'Invalid file type. Allowed: Images (JPEG, PNG, GIF, WebP), PDF, DOC, DOCX',
            ];
        }

        // Validate file size (max 5MB)
        if ($uploadedFile->getSize() > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'filename' => $uploadedFile->getClientFilename(),
                'error' => 'File size too large. Maximum 5MB allowed',
            ];
        }

        // Create uploads directory if it doesn't exist
        $uploadsDir = WWW_ROOT . 'img' . DS . 'uploads';
        if (!is_dir($uploadsDir)) {
            if (!mkdir($uploadsDir, 0755, true)) {
                return [
                    'success' => false,
                    'filename' => $uploadedFile->getClientFilename(),
                    'error' => 'Failed to create uploads directory',
                ];
            }
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
                'original_name' => $originalName,
                'url' => '/img/uploads/' . $filename,
                'size' => $uploadedFile->getSize(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'filename' => $originalName,
                'error' => 'Failed to upload file: ' . $e->getMessage(),
            ];
        }
    }
}
