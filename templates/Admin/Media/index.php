<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php $this->assign('title', 'Media Library'); ?>

<div class="media index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Media Library') ?></h3>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload"></i> Upload Files
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">File Type</label>
                        <select class="form-control" id="file-type-filter">
                            <option value="">All Files</option>
                            <option value="image">Images</option>
                            <option value="document">Documents</option>
                            <option value="video">Videos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <select class="form-control" id="date-filter">
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Files</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="grid-view">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="list-view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="media-grid" class="media-grid">
                        <?php
                        // Scan uploads directory for files
                        $uploadsDir = WWW_ROOT . 'img' . DS . 'uploads';
                        if (is_dir($uploadsDir)) {
                            $files = array_diff(scandir($uploadsDir), array('.', '..'));
                            foreach ($files as $file) {
                                $filePath = $uploadsDir . DS . $file;
                                if (is_file($filePath)) {
                                    $fileInfo = pathinfo($file);
                                    $fileSize = filesize($filePath);
                                    $fileDate = date('Y-m-d H:i:s', filemtime($filePath));
                                    $isImage = in_array(strtolower($fileInfo['extension'] ?? ''), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    ?>
                                    <div class="media-item" data-file="<?= h($file) ?>" data-type="<?= $isImage ? 'image' : 'document' ?>">
                                        <div class="media-thumbnail">
                                            <?php if ($isImage): ?>
                                                <img src="/img/uploads/<?= h($file) ?>" alt="<?= h($fileInfo['filename']) ?>">
                                            <?php else: ?>
                                                <div class="file-icon">
                                                    <i class="fas fa-file"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="media-info">
                                            <div class="media-title"><?= h($fileInfo['filename']) ?></div>
                                            <div class="media-meta">
                                                <small class="text-muted">
                                                    <?= number_format($fileSize / 1024, 1) ?> KB • <?= date('M j, Y', filemtime($filePath)) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="media-actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary copy-url" 
                                                    data-url="/img/uploads/<?= h($file) ?>" title="Copy URL">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-file" 
                                                    data-file="<?= h($file) ?>" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        } else {
                            echo '<div class="text-center py-4 text-muted">No files found in uploads directory.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select files to upload</label>
                    <input type="file" class="form-control" id="file-upload" name="files[]" multiple>
                    <div class="form-text">Maximum file size: 5MB per file. Supported formats: Images (JPEG, PNG, GIF, WebP), Documents (PDF, DOC, DOCX)</div>
                </div>
                <div id="upload-progress" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="upload-btn">Upload</button>
            </div>
        </div>
    </div>
</div>

<style>
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.media-item {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    text-align: center;
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}

.media-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.media-thumbnail {
    margin-bottom: 0.5rem;
}

.media-thumbnail img {
    max-width: 100%;
    max-height: 100px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.file-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.media-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
    word-break: break-all;
}

.media-actions {
    margin-top: 0.5rem;
}

.media-actions .btn {
    margin: 0 0.125rem;
}

@media (max-width: 768px) {
    .media-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy URL functionality
    document.querySelectorAll('.copy-url').forEach(button => {
        button.addEventListener('click', function() {
            const url = window.location.origin + this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                // Show success feedback
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-check';
                setTimeout(() => {
                    icon.className = originalClass;
                }, 1000);
            });
        });
    });

    // File type filter
    document.getElementById('file-type-filter').addEventListener('change', function() {
        const filterType = this.value;
        const items = document.querySelectorAll('.media-item');
        
        items.forEach(item => {
            if (!filterType || item.getAttribute('data-type') === filterType) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Upload functionality
    document.getElementById('upload-btn').addEventListener('click', function() {
        const fileInput = document.getElementById('file-upload');
        const files = fileInput.files;
        
        if (files.length === 0) {
            alert('Please select files to upload.');
            return;
        }
        
        const progressDiv = document.getElementById('upload-progress');
        const progressBar = progressDiv.querySelector('.progress-bar');
        const uploadBtn = this;
        
        // Show progress bar and disable button
        progressDiv.style.display = 'block';
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Uploading...';
        
        // Create form data
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        
        // Upload files
        fetch('<?= $this->Url->build(['action' => 'upload']) ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            progressBar.style.width = '100%';
            
            if (data.success) {
                alert(data.message);
                // Reload page to show new files
                window.location.reload();
            } else {
                alert('Upload failed: ' + data.message);
                console.error('Upload errors:', data.results);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('An error occurred during upload.');
        })
        .finally(() => {
            // Reset UI
            progressDiv.style.display = 'none';
            progressBar.style.width = '0%';
            uploadBtn.disabled = false;
            uploadBtn.textContent = 'Upload';
            fileInput.value = '';
        });
    });
    
    // Delete file functionality
    document.querySelectorAll('.delete-file').forEach(button => {
        button.addEventListener('click', function() {
            const filename = this.getAttribute('data-file');
            
            if (!confirm('Are you sure you want to delete this file?')) {
                return;
            }
            
            fetch('<?= $this->Url->build(['action' => 'deleteFile']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                },
                body: JSON.stringify({
                    filename: filename
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the file item from the DOM
                    this.closest('.media-item').remove();
                    alert('File deleted successfully.');
                } else {
                    alert('Delete failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the file.');
            });
        });
    });
    
    // Upload progress simulation for better UX
    function simulateProgress(progressBar) {
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 90) {
                clearInterval(interval);
                return;
            }
            progressBar.style.width = progress + '%';
        }, 200);
    }
});
</script>