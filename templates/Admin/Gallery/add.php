<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GalleryItem $galleryItem
 */
?>
<?php $this->assign('title', 'Adauga imagine in galerie'); ?>

<div class="gallery form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Adauga imagine in galerie') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> ' . __('Inapoi la galerie'),
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?= $this->Form->create($galleryItem) ?>

                    <div class="mb-3">
                        <label class="form-label"><?= __('Imagine') ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <?= $this->Form->control('image_url', [
                                'label' => false,
                                'class' => 'form-control',
                                'id' => 'image-url-input',
                                'placeholder' => 'Selectati o imagine...',
                                'readonly' => true,
                                'required' => true
                            ]) ?>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mediaBrowserModal">
                                <i class="fas fa-images"></i> <?= __('Selecteaza') ?>
                            </button>
                        </div>
                        <div class="form-text"><?= __('Selectati o imagine din biblioteca media') ?></div>
                    </div>

                    <div id="image-preview" class="mb-3" style="display: none;">
                        <label class="form-label"><?= __('Previzualizare') ?></label>
                        <div class="border rounded p-3 text-center bg-light">
                            <img id="preview-img" src="" alt="Preview" style="max-height: 200px; max-width: 100%;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <?= $this->Form->control('title', [
                            'label' => __('Titlu (pentru caption)'),
                            'class' => 'form-control',
                            'placeholder' => 'ex: Sala de operatie moderna'
                        ]) ?>
                        <div class="form-text"><?= __('Acest titlu va aparea in lightbox cand se face click pe imagine') ?></div>
                    </div>

                    <div class="mb-3">
                        <?= $this->Form->control('alt_text', [
                            'label' => __('Text alternativ (accesibilitate)'),
                            'class' => 'form-control',
                            'placeholder' => 'ex: Fotografie cu sala de operatie'
                        ]) ?>
                        <div class="form-text"><?= __('Descriere pentru accesibilitate si SEO') ?></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <?= $this->Form->checkbox('is_active', [
                                'class' => 'form-check-input',
                                'id' => 'is-active',
                                'checked' => true
                            ]) ?>
                            <label class="form-check-label" for="is-active"><?= __('Activa (vizibila pe site)') ?></label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <?= $this->Html->link(__('Anuleaza'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
                        <?= $this->Form->button('<i class="fas fa-save"></i> ' . __('Salveaza'), [
                            'class' => 'btn btn-primary',
                            'escape' => false
                        ]) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> <?= __('Ajutor') ?></h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        <strong><?= __('Imagine:') ?></strong> <?= __('Selectati o imagine din biblioteca media sau din folderul galeriei existente.') ?>
                    </p>
                    <p class="small text-muted mb-2">
                        <strong><?= __('Titlu:') ?></strong> <?= __('Optional - apare ca si caption in lightbox.') ?>
                    </p>
                    <p class="small text-muted mb-0">
                        <strong><?= __('Text alternativ:') ?></strong> <?= __('Recomandat pentru accesibilitate.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Media Browser Modal -->
<div class="modal fade" id="mediaBrowserModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-images"></i> <?= __('Selecteaza imagine') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="media-search" placeholder="Cauta imagine...">
                </div>
                <div id="media-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted"><?= __('Se incarca imaginile...') ?></p>
                </div>
                <div id="media-grid" class="media-browser-grid" style="display: none;"></div>
                <div id="media-empty" class="text-center py-4 text-muted" style="display: none;">
                    <i class="fas fa-images fa-2x mb-2"></i>
                    <p><?= __('Nu s-au gasit imagini.') ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Anuleaza') ?></button>
                <button type="button" class="btn btn-primary" id="select-image-btn" disabled>
                    <i class="fas fa-check"></i> <?= __('Selecteaza') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.media-browser-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.75rem;
    max-height: 400px;
    overflow-y: auto;
}

.media-browser-item {
    border: 2px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.2s;
}

.media-browser-item:hover {
    border-color: #adb5bd;
    transform: scale(1.02);
}

.media-browser-item.selected {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.media-browser-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
}

.media-browser-item .filename {
    font-size: 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #6c757d;
}

.media-browser-item .source-badge {
    font-size: 0.65rem;
    padding: 0.1rem 0.3rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedImage = null;
    let allImages = [];

    const mediaBrowserModal = document.getElementById('mediaBrowserModal');
    const mediaGrid = document.getElementById('media-grid');
    const mediaLoading = document.getElementById('media-loading');
    const mediaEmpty = document.getElementById('media-empty');
    const mediaSearch = document.getElementById('media-search');
    const selectImageBtn = document.getElementById('select-image-btn');
    const imageUrlInput = document.getElementById('image-url-input');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // Load images when modal opens
    mediaBrowserModal.addEventListener('show.bs.modal', function() {
        loadMediaImages();
    });

    // Search functionality
    mediaSearch.addEventListener('input', function() {
        filterImages(this.value);
    });

    // Select image button
    selectImageBtn.addEventListener('click', function() {
        if (selectedImage) {
            imageUrlInput.value = selectedImage.url;
            previewImg.src = selectedImage.url;
            imagePreview.style.display = 'block';

            // Close modal
            bootstrap.Modal.getInstance(mediaBrowserModal).hide();
        }
    });

    function loadMediaImages() {
        mediaLoading.style.display = 'block';
        mediaGrid.style.display = 'none';
        mediaEmpty.style.display = 'none';
        selectedImage = null;
        selectImageBtn.disabled = true;

        fetch('<?= $this->Url->build(['action' => 'browseMedia']) ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            mediaLoading.style.display = 'none';

            if (data.success && data.files.length > 0) {
                allImages = data.files;
                renderImages(allImages);
                mediaGrid.style.display = 'grid';
            } else {
                mediaEmpty.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading images:', error);
            mediaLoading.style.display = 'none';
            mediaEmpty.style.display = 'block';
        });
    }

    function renderImages(images) {
        mediaGrid.innerHTML = '';

        images.forEach(image => {
            const item = document.createElement('div');
            item.className = 'media-browser-item';
            item.innerHTML = `
                <img src="${image.url}" alt="${image.title}">
                <div class="filename">${image.title}</div>
                <span class="badge ${image.source === 'gallery' ? 'bg-info' : 'bg-secondary'} source-badge">
                    ${image.source === 'gallery' ? 'Galerie' : 'Media'}
                </span>
            `;

            item.addEventListener('click', function() {
                // Remove selection from others
                document.querySelectorAll('.media-browser-item.selected').forEach(el => {
                    el.classList.remove('selected');
                });

                // Select this one
                this.classList.add('selected');
                selectedImage = image;
                selectImageBtn.disabled = false;
            });

            // Double-click to select immediately
            item.addEventListener('dblclick', function() {
                selectedImage = image;
                selectImageBtn.click();
            });

            mediaGrid.appendChild(item);
        });
    }

    function filterImages(query) {
        const filtered = allImages.filter(image =>
            image.title.toLowerCase().includes(query.toLowerCase()) ||
            image.filename.toLowerCase().includes(query.toLowerCase())
        );
        renderImages(filtered);

        if (filtered.length === 0) {
            mediaGrid.style.display = 'none';
            mediaEmpty.style.display = 'block';
        } else {
            mediaGrid.style.display = 'grid';
            mediaEmpty.style.display = 'none';
        }
    }
});
</script>
