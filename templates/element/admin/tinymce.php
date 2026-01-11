<?php
/**
 * TinyMCE Editor Element with Media Library Integration
 *
 * @var \App\View\AppView $this
 * @var string $selector The CSS selector for the textarea (default: '.tinymce-editor')
 */

use Cake\Core\Configure;

$tinymceKey = Configure::read('ApiKeys.tinymce', 'no-api-key');
$selector = $selector ?? '.tinymce-editor';
$csrfToken = $this->request->getAttribute('csrfToken');
$browseUrl = $this->Url->build(['controller' => 'Media', 'action' => 'browse', 'prefix' => 'Admin']);
$uploadUrl = $this->Url->build(['controller' => 'Media', 'action' => 'upload', 'prefix' => 'Admin']);
?>

<!-- Media Browser Modal -->
<div class="modal fade" id="mediaBrowserModal" tabindex="-1" aria-labelledby="mediaBrowserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaBrowserModalLabel">
                    <i class="fas fa-images me-2"></i>Selectează imagine din bibliotecă
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Upload Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <input type="file" class="form-control" id="media-browser-upload" accept="image/*" multiple>
                                <small class="text-muted">Încarcă imagini noi (max 5MB, formate: JPEG, PNG, GIF, WebP)</small>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-primary" id="media-browser-upload-btn">
                                    <i class="fas fa-upload me-1"></i> Încarcă
                                </button>
                            </div>
                        </div>
                        <div id="media-browser-upload-progress" class="mt-2" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-3">
                    <input type="text" class="form-control" id="media-browser-search" placeholder="Caută imagini...">
                </div>

                <!-- Images Grid -->
                <div id="media-browser-grid" class="media-browser-grid">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Se încarcă...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                <button type="button" class="btn btn-primary" id="media-browser-select-btn" disabled>
                    <i class="fas fa-check me-1"></i> Inserează imaginea
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Override z-index to appear above TinyMCE dialogs */
#mediaBrowserModal {
    z-index: 99999 !important;
}
#mediaBrowserModal + .modal-backdrop,
.modal-backdrop {
    z-index: 99998 !important;
}

.media-browser-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
    padding: 0.5rem;
}

.media-browser-item {
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fff;
}

.media-browser-item:hover {
    border-color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.media-browser-item.selected {
    border-color: #0d6efd;
    background: #e7f1ff;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.media-browser-thumb {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 0.25rem;
    background: #f8f9fa;
}

.media-browser-name {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #495057;
}

.media-browser-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.media-browser-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
}
</style>

<script src="https://cdn.tiny.cloud/1/<?= h($tinymceKey) ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function() {
    // Media Browser State
    let selectedImage = null;
    let pickerCallback = null;
    let mediaBrowserModal = null;

    // Initialize Media Browser
    function initMediaBrowser() {
        mediaBrowserModal = new bootstrap.Modal(document.getElementById('mediaBrowserModal'));

        // Load images when modal opens
        document.getElementById('mediaBrowserModal').addEventListener('show.bs.modal', loadMediaImages);

        // Search functionality
        document.getElementById('media-browser-search').addEventListener('input', filterImages);

        // Select button
        document.getElementById('media-browser-select-btn').addEventListener('click', selectImage);

        // Upload functionality
        document.getElementById('media-browser-upload-btn').addEventListener('click', uploadImages);
    }

    // Load images from server
    function loadMediaImages() {
        const grid = document.getElementById('media-browser-grid');
        grid.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

        fetch('<?= $browseUrl ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.files.length > 0) {
                renderImages(data.files);
            } else {
                grid.innerHTML = `
                    <div class="media-browser-empty">
                        <i class="fas fa-images"></i>
                        <p>Nu există imagini în bibliotecă.</p>
                        <p class="text-muted">Încarcă imagini folosind formularul de mai sus.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading images:', error);
            grid.innerHTML = '<div class="media-browser-empty"><i class="fas fa-exclamation-triangle text-danger"></i><p>Eroare la încărcarea imaginilor.</p></div>';
        });
    }

    // Render images in grid
    function renderImages(files) {
        const grid = document.getElementById('media-browser-grid');
        grid.innerHTML = '';

        files.forEach(file => {
            const item = document.createElement('div');
            item.className = 'media-browser-item';
            item.dataset.url = file.url;
            item.dataset.title = file.title;
            item.innerHTML = `
                <img src="${file.url}" alt="${file.title}" class="media-browser-thumb" loading="lazy">
                <div class="media-browser-name" title="${file.title}">${file.title}</div>
            `;

            item.addEventListener('click', function() {
                // Deselect all
                document.querySelectorAll('.media-browser-item.selected').forEach(el => el.classList.remove('selected'));
                // Select this one
                this.classList.add('selected');
                selectedImage = {
                    url: this.dataset.url,
                    title: this.dataset.title
                };
                document.getElementById('media-browser-select-btn').disabled = false;
            });

            grid.appendChild(item);
        });
    }

    // Filter images by search
    function filterImages() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.media-browser-item').forEach(item => {
            const title = item.dataset.title.toLowerCase();
            item.style.display = title.includes(search) ? 'block' : 'none';
        });
    }

    // Select image and return to TinyMCE
    function selectImage() {
        if (selectedImage && pickerCallback) {
            pickerCallback(selectedImage.url, { alt: selectedImage.title, title: selectedImage.title });
            mediaBrowserModal.hide();
            selectedImage = null;
            document.getElementById('media-browser-select-btn').disabled = true;
        }
    }

    // Upload images
    function uploadImages() {
        const fileInput = document.getElementById('media-browser-upload');
        const files = fileInput.files;

        if (files.length === 0) {
            alert('Selectează imagini pentru încărcare.');
            return;
        }

        const progressDiv = document.getElementById('media-browser-upload-progress');
        const progressBar = progressDiv.querySelector('.progress-bar');
        const uploadBtn = document.getElementById('media-browser-upload-btn');

        progressDiv.style.display = 'block';
        uploadBtn.disabled = true;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        fetch('<?= $uploadUrl ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?= $csrfToken ?>'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            progressBar.style.width = '100%';
            if (data.success) {
                // Reload images
                loadMediaImages();
                fileInput.value = '';
            } else {
                alert('Eroare: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Eroare la încărcare.');
        })
        .finally(() => {
            setTimeout(() => {
                progressDiv.style.display = 'none';
                progressBar.style.width = '0%';
                uploadBtn.disabled = false;
            }, 500);
        });
    }

    // Open media browser (called from TinyMCE)
    window.openMediaBrowser = function(callback) {
        pickerCallback = callback;
        selectedImage = null;
        document.getElementById('media-browser-select-btn').disabled = true;
        document.querySelectorAll('.media-browser-item.selected').forEach(el => el.classList.remove('selected'));
        mediaBrowserModal.show();
    };

    // Initialize TinyMCE
    document.addEventListener('DOMContentLoaded', function() {
        initMediaBrowser();

        tinymce.init({
            selector: '<?= h($selector) ?>',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'link image media | removeformat | code fullscreen | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
            branding: false,
            promotion: false,

            // Image settings
            image_title: true,
            automatic_uploads: false,
            image_advtab: true,

            // File picker for images
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    window.openMediaBrowser(callback);
                }
            },

            setup: function(editor) {
                // Ensure content is saved back to textarea before form submission
                editor.on('submit', function() {
                    editor.save();
                });

                // Update textarea on every change
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });
})();
</script>
