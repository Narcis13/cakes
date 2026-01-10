<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NewsCategory $newsCategory
 */
?>
<?php $this->assign('title', 'Editează categorie știri'); ?>

<div class="newsCategories edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Editează categorie știri: {0}', h($newsCategory->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vizualizează',
                ['action' => 'view', $newsCategory->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?= $this->Form->create($newsCategory) ?>

                    <fieldset>
                        <legend><?= __('Informații categorie') ?></legend>

                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Nume'],
                            'required' => true,
                            'placeholder' => 'ex: Noutăți spital, Sfaturi de sănătate, Evenimente'
                        ]) ?>

                        <?= $this->Form->control('slug', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Slug URL'],
                            'placeholder' => 'slug-url-categorie'
                        ]) ?>

                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                            'rows' => 4,
                            'placeholder' => 'Scurtă descriere a acestei categorii (opțional)...'
                        ]) ?>
                    </fieldset>

                    <div class="form-actions mt-4">
                        <?= $this->Form->button(__('Actualizează categoria'), [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        <?= $this->Html->link(__('Anulează'),
                            ['action' => 'view', $newsCategory->id],
                            ['class' => 'btn btn-outline-secondary ms-2']
                        ) ?>
                        <?= $this->Form->postLink(__('Șterge'),
                            ['action' => 'delete', $newsCategory->id],
                            [
                                'confirm' => __('Sigur doriți să ștergeți această categorie? Această acțiune nu poate fi anulată.'),
                                'class' => 'btn btn-outline-danger ms-2'
                            ]
                        ) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalii categorie</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Creat:</strong><br>
                    <small class="text-muted"><?= h($newsCategory->created->format('j M Y H:i')) ?></small></p>

                    <p class="mb-0"><strong>Ultima modificare:</strong><br>
                    <small class="text-muted"><?= h($newsCategory->modified->format('j M Y H:i')) ?></small></p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Sfaturi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important:</strong> Schimbarea slug-ului va afecta URL-urile existente către această categorie.
                    </div>

                    <p class="mb-2"><strong>Bune practici:</strong></p>
                    <ul class="small mb-0">
                        <li>Păstrați numele categoriilor clare și concise</li>
                        <li>Actualizați descrierile pentru a ajuta autorii să aleagă categoria potrivită</li>
                        <li>Evitați schimbarea slug-urilor dacă articolele sunt deja publicate</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name only if slug is empty
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('blur', function() {
            if (!slugInput.value && nameInput.value) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }
});
</script>
