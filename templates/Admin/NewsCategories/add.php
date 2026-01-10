<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\NewsCategory $newsCategory
 */
?>
<?php $this->assign('title', 'Adaugă categorie știri'); ?>

<div class="newsCategories add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Adaugă categorie știri') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Înapoi la listă',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
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
                            'label' => ['class' => 'form-label', 'text' => 'Slug URL (lăsați gol pentru generare automată)'],
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

                    <div class="mt-4">
                        <?= $this->Form->button(__('Salvează categoria'), [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        <?= $this->Html->link(__('Anulează'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary ms-2']
                        ) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Sfaturi</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Numele categoriei:</strong></p>
                    <ul class="small">
                        <li>Folosiți nume clare și descriptive</li>
                        <li>Păstrați-l scurt și memorabil</li>
                        <li>Exemple: „Noutăți spital", „Sfaturi de sănătate", „Evenimente"</li>
                    </ul>

                    <p class="mb-2 mt-3"><strong>Slug URL:</strong></p>
                    <ul class="small mb-0">
                        <li>Va fi generat automat din nume</li>
                        <li>Folosit în URL-uri pentru paginile categoriei</li>
                        <li>Ar trebui să fie cu litere mici și cu cratime</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
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
