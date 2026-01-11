<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\GalleryItem> $galleryItems
 */
?>
<?php $this->assign('title', 'Galerie Foto'); ?>

<div class="gallery index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Galerie Foto') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-plus"></i> ' . __('Adauga imagine'),
                ['action' => 'add'],
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <?php
    $itemCount = iterator_count($galleryItems);
    $galleryItems->rewind();
    if ($itemCount > 12): ?>
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <?= __('Aveti {0} imagini in galerie. Pentru o afisare optima, recomandam maximum 12 imagini.', $itemCount) ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?= __('Imagini in galerie') ?> (<?= $itemCount ?>)</h5>
                <small class="text-muted"><i class="fas fa-arrows-alt"></i> <?= __('Trageti pentru a reordona') ?></small>
            </div>
        </div>
        <div class="card-body">
            <?php if ($itemCount > 0): ?>
            <div id="gallery-grid" class="gallery-grid">
                <?php foreach ($galleryItems as $item): ?>
                <div class="gallery-item" data-id="<?= h($item->id) ?>">
                    <div class="drag-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div class="gallery-thumbnail">
                        <img src="<?= h($item->image_url) ?>" alt="<?= h($item->alt_text ?: $item->title) ?>">
                    </div>
                    <div class="gallery-info">
                        <div class="gallery-title"><?= h($item->title ?: 'Fara titlu') ?></div>
                    </div>
                    <div class="gallery-controls">
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-active" type="checkbox"
                                   data-id="<?= h($item->id) ?>"
                                   <?= $item->is_active ? 'checked' : '' ?>>
                            <label class="form-check-label small"><?= $item->is_active ? 'Activa' : 'Inactiva' ?></label>
                        </div>
                    </div>
                    <div class="gallery-actions">
                        <?= $this->Html->link(
                            '<i class="fas fa-edit"></i>',
                            ['action' => 'edit', $item->id],
                            ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Editeaza']
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash"></i>',
                            ['action' => 'delete', $item->id],
                            [
                                'class' => 'btn btn-sm btn-outline-danger',
                                'escape' => false,
                                'title' => 'Sterge',
                                'confirm' => __('Sigur doriti sa stergeti aceasta imagine din galerie?')
                            ]
                        ) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-images fa-3x mb-3"></i>
                <p><?= __('Nu exista imagini in galerie.') ?></p>
                <?= $this->Html->link(
                    '<i class="fas fa-plus"></i> ' . __('Adauga prima imagine'),
                    ['action' => 'add'],
                    ['class' => 'btn btn-primary', 'escape' => false]
                ) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.gallery-item {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.75rem;
    text-align: center;
    position: relative;
    background: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
}

.gallery-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.gallery-item.sortable-ghost {
    opacity: 0.4;
    background: #e9ecef;
}

.gallery-item.sortable-drag {
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.drag-handle {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    cursor: grab;
    color: #adb5bd;
    padding: 0.25rem;
}

.drag-handle:hover {
    color: #495057;
}

.drag-handle:active {
    cursor: grabbing;
}

.gallery-thumbnail {
    margin-bottom: 0.5rem;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 0.25rem;
    background: #f8f9fa;
}

.gallery-thumbnail img {
    max-width: 100%;
    max-height: 120px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.gallery-title {
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gallery-controls {
    margin-bottom: 0.5rem;
}

.gallery-controls .form-check {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.gallery-actions {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
}

.gallery-actions .btn {
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }

    .gallery-thumbnail {
        height: 100px;
    }
}
</style>

<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryGrid = document.getElementById('gallery-grid');

    if (galleryGrid) {
        // Initialize SortableJS
        const sortable = new Sortable(galleryGrid, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Get new order of item IDs
                const items = galleryGrid.querySelectorAll('.gallery-item');
                const itemIds = Array.from(items).map(item => item.getAttribute('data-id'));

                // Send reorder request
                fetch('<?= $this->Url->build(['action' => 'reorder']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                    },
                    body: JSON.stringify({
                        item_ids: itemIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Reordonarea a esuat. Va rugam incercati din nou.');
                    }
                })
                .catch(error => {
                    console.error('Eroare reordonare:', error);
                    alert('A aparut o eroare. Va rugam incercati din nou.');
                });
            }
        });
    }

    // Toggle active status
    document.querySelectorAll('.toggle-active').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const itemId = this.getAttribute('data-id');
            const label = this.nextElementSibling;
            const checkbox = this;

            fetch('<?= $this->Url->build(['action' => 'toggleActive']) ?>/' + itemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    label.textContent = data.is_active ? 'Activa' : 'Inactiva';
                } else {
                    // Revert checkbox
                    checkbox.checked = !checkbox.checked;
                    alert('Schimbarea starii a esuat.');
                }
            })
            .catch(error => {
                console.error('Eroare toggle:', error);
                checkbox.checked = !checkbox.checked;
                alert('A aparut o eroare.');
            });
        });
    });
});
</script>
