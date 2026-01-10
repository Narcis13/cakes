<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\News $newsItem
 * @var array $categories
 * @var array $authors
 */
?>
<?php $this->assign('title', 'Editează articol știri'); ?>

<!-- Include TinyMCE for rich text editing -->
<?= $this->element('admin/tinymce', ['selector' => '#content-editor']) ?>

<div class="news edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Editează articol știri') ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vizualizează',
                ['action' => 'view', $newsItem->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <?php if ($newsItem->getErrors()): ?>
    <div class="alert alert-danger">
        <h5>Vă rugăm să corectați următoarele erori:</h5>
        <ul class="mb-0">
            <?php foreach ($newsItem->getErrors() as $field => $errors): ?>
                <?php foreach ($errors as $error): ?>
                    <li><?= h($field) ?>: <?= h($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?= $this->Form->create($newsItem, ['type' => 'file']) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Detalii articol</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('title', [
                        'class' => 'form-control form-control-lg',
                        'label' => ['class' => 'form-label', 'text' => 'Titlu'],
                        'required' => true,
                        'placeholder' => 'Introduceți titlul articolului...'
                    ]) ?>

                    <?= $this->Form->control('slug', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Slug URL'],
                        'placeholder' => 'slug-url-articol'
                    ]) ?>

                    <?= $this->Form->control('excerpt', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Rezumat'],
                        'rows' => 3,
                        'placeholder' => 'Scurt rezumat al articolului (opțional)...'
                    ]) ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Conținut</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('content', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'content-editor',
                        'rows' => 15,
                        'required' => false
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Publicare</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('is_published', [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'label' => [
                            'class' => 'form-check-label',
                            'text' => 'Publică acest articol'
                        ],
                        'templates' => [
                            'checkboxWrapper' => '<div class="form-check mb-3">{{label}}</div>',
                            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                        ]
                    ]) ?>

                    <?= $this->Form->control('publish_date', [
                        'type' => 'datetime-local',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Data publicării'],
                        'value' => $newsItem->publish_date ? $newsItem->publish_date->format('Y-m-d\TH:i') : null
                    ]) ?>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> Vizualizări: <?= $this->Number->format($newsItem->views_count) ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Categorizare</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->control('category_id', [
                        'type' => 'select',
                        'options' => $categories,
                        'empty' => 'Selectați categoria',
                        'class' => 'form-select mb-3',
                        'label' => ['class' => 'form-label', 'text' => 'Categorie']
                    ]) ?>

                    <?= $this->Form->control('author_id', [
                        'type' => 'select',
                        'options' => $authors,
                        'empty' => 'Selectați autorul',
                        'class' => 'form-select',
                        'label' => ['class' => 'form-label', 'text' => 'Autor']
                    ]) ?>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Imagine principală</h5>
                </div>
                <div class="card-body">
                    <?php if ($newsItem->featured_image): ?>
                    <div class="current-image mb-3">
                        <label class="form-label">Imaginea curentă:</label>
                        <img src="<?= $this->Url->build('/img/news/' . $newsItem->featured_image) ?>"
                             class="img-fluid rounded"
                             alt="<?= h($newsItem->title) ?>">
                    </div>
                    <?php endif; ?>

                    <?= $this->Form->control('featured_image_file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => $newsItem->featured_image ? 'Schimbă imaginea' : 'Încarcă imagine'],
                        'accept' => 'image/*'
                    ]) ?>
                    <div class="form-text">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Dimensiune recomandată: 1200x630px. Dimensiune maximă: 10MB.
                        </small>
                    </div>

                    <div id="imagePreview" style="display: none;" class="mt-3">
                        <label class="form-label">Previzualizare imagine nouă:</label>
                        <img id="previewImg" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body text-muted small">
                    <p class="mb-1"><strong>Detalii articol:</strong></p>
                    Creat: <?= h($newsItem->created->format('j M Y H:i')) ?><br>
                    Modificat: <?= h($newsItem->modified->format('j M Y H:i')) ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <?= $this->Form->button(__('Actualizează articolul'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Anulează'),
                    ['action' => 'view', $newsItem->id],
                    ['class' => 'btn btn-outline-secondary']
                ) ?>
                <?= $this->Form->postLink(__('Șterge'),
                    ['action' => 'delete', $newsItem->id],
                    [
                        'confirm' => __('Sigur doriți să ștergeți acest articol? Această acțiune nu poate fi anulată.'),
                        'class' => 'btn btn-outline-danger'
                    ]
                ) ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
// Image preview
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="featured_image_file"]');
    const previewDiv = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewDiv.style.display = 'none';
            }
        });
    }
});
</script>
