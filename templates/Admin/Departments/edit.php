<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 * @var array $staff
 */
?>
<?php $this->assign('title', 'Editează departament'); ?>

<div class="department edit content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Editează departament: {0}', h($department->name)) ?></h3>
        <div>
            <?= $this->Html->link(
                '<i class="fas fa-eye"></i> Vizualizează',
                ['action' => 'view', $department->id],
                ['class' => 'btn btn-outline-primary me-2', 'escape' => false]
            ) ?>
            <?= $this->Html->link(
                '<i class="fas fa-arrow-left"></i> Înapoi la listă',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($department, ['type' => 'file']) ?>

            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Informații de bază') ?></legend>

                        <?= $this->Form->control('name', [
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Nume'],
                            'required' => true
                        ]) ?>

                        <?= $this->Form->control('description', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                            'rows' => 4,
                            'placeholder' => 'Introduceți descrierea departamentului...'
                        ]) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('phone', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Telefon'],
                                    'placeholder' => 'ex., +40-234-567-890'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'departament@spital.ro'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('floor_location', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Etaj/Locație'],
                                    'placeholder' => 'ex., Etaj 3, Aripa A'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('head_doctor_id', [
                                    'type' => 'select',
                                    'options' => $staff,
                                    'empty' => 'Selectați medicul șef',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Medic șef']
                                ]) ?>
                            </div>
                        </div>

                        <?= $this->Form->control('is_active', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => [
                                'class' => 'form-check-label',
                                'text' => 'Departamentul este activ'
                            ],
                            'templates' => [
                                'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                            ]
                        ]) ?>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Imagine departament') ?></legend>

                        <?php if ($department->picture): ?>
                        <div class="current-image mb-3">
                            <label class="form-label">Imagine curentă:</label>
                            <div class="text-center">
                                <img src="<?= $this->Url->build('/img/departments/' . $department->picture) ?>"
                                     class="img-fluid rounded border"
                                     style="max-height: 200px; object-fit: cover;"
                                     alt="<?= h($department->name) ?>">
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <?= $this->Form->control('picture_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => $department->picture ? 'Schimbă imaginea' : 'Încarcă imagine'],
                                'accept' => 'image/*'
                            ]) ?>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Formate acceptate: JPG, PNG, GIF, WebP. Dimensiune maximă: 5MB.
                                </small>
                            </div>
                        </div>

                        <div id="imagePreview" style="display: none;">
                            <label class="form-label">Previzualizare imagine nouă:</label>
                            <img id="previewImg" class="img-fluid rounded border" style="max-height: 200px;">
                        </div>
                    </fieldset>
                </div>
            </div>

            <fieldset class="mt-4">
                <legend><?= __('Informații despre servicii') ?></legend>

                <?= $this->Form->control('services_html', [
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'label' => ['class' => 'form-label', 'text' => 'Descriere servicii (HTML)'],
                    'rows' => 8,
                    'placeholder' => 'Introduceți informații detaliate despre serviciile oferite de acest departament. Puteți folosi tag-uri HTML pentru formatare.'
                ]) ?>

                <div class="form-text">
                    <small class="text-muted">
                        <i class="fas fa-code"></i>
                        Puteți folosi tag-uri HTML precum &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;em&gt;, etc. pentru o formatare mai bună.
                    </small>
                </div>
            </fieldset>

            <div class="form-actions mt-4">
                <?= $this->Form->button(__('Actualizează departament'), [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= $this->Html->link(__('Anulează'), ['action' => 'index'], [
                    'class' => 'btn btn-secondary ms-2'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

            <div class="mt-3">
                <?= $this->Form->postLink(
                    __('Șterge departament'),
                    ['action' => 'delete', $department->id],
                    [
                        'class' => 'btn btn-danger',
                        'confirm' => __('Sunteți sigur că doriți să ștergeți "{0}"?', $department->name)
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="picture_file"]');
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
