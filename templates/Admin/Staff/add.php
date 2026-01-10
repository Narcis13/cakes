<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staffMember
 * @var array $departments
 */
?>
<?php $this->assign('title', 'Adaugă membru personal'); ?>

<div class="staff add content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Adaugă membru personal') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Înapoi la listă',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($staffMember, ['type' => 'file']) ?>

            <div class="row">
                <div class="col-md-8">
                    <fieldset>
                        <legend><?= __('Informații personale') ?></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('first_name', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Prenume'],
                                    'required' => true,
                                    'placeholder' => 'Ion'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('last_name', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Nume'],
                                    'required' => true,
                                    'placeholder' => 'Popescu'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('title', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Titlu/Poziție'],
                                    'placeholder' => 'ex., Dr., Șef departament, Chirurg senior'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('specialization_id', [
                                    'type' => 'select',
                                    'options' => $specializations,
                                    'empty' => 'Selectați specializarea',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Specializare']
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $this->Form->control('staff_type', [
                                    'type' => 'select',
                                    'options' => [
                                        'doctor' => 'Medic',
                                        'nurse' => 'Asistent medical',
                                        'technician' => 'Tehnician',
                                        'administrator' => 'Administrator',
                                        'other' => 'Altul'
                                    ],
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Tip personal'],
                                    'default' => 'doctor'
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('department_id', [
                                    'type' => 'select',
                                    'options' => $departments,
                                    'empty' => 'Selectați departamentul',
                                    'class' => 'form-select',
                                    'label' => ['class' => 'form-label', 'text' => 'Departament']
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('years_experience', [
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Ani de experiență'],
                                    'min' => 0,
                                    'max' => 60,
                                    'placeholder' => '0'
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mt-4">
                        <legend><?= __('Informații de contact') ?></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('phone', [
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label', 'text' => 'Telefon'],
                                    'placeholder' => '+40-234-567-890'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'type' => 'email',
                                    'class' => 'form-control',
                                    'label' => ['class' => 'form-label'],
                                    'placeholder' => 'medic@spital.ro'
                                ]) ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mt-4">
                        <legend><?= __('Biografie') ?></legend>

                        <?= $this->Form->control('bio', [
                            'type' => 'textarea',
                            'class' => 'form-control',
                            'label' => ['class' => 'form-label', 'text' => 'Biografie'],
                            'rows' => 5,
                            'placeholder' => 'Scurtă biografie profesională, calificări și realizări...'
                        ]) ?>
                    </fieldset>

                    <div class="mt-3">
                        <?= $this->Form->control('is_active', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => [
                                'class' => 'form-check-label',
                                'text' => 'Membrul personalului este activ'
                            ],
                            'templates' => [
                                'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
                                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
                            ],
                            'checked' => true
                        ]) ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <fieldset>
                        <legend><?= __('Fotografie de profil') ?></legend>

                        <div class="mb-3">
                            <?= $this->Form->control('photo_file', [
                                'type' => 'file',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Încarcă fotografie'],
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
                            <label class="form-label">Previzualizare fotografie:</label>
                            <div class="text-center">
                                <img id="previewImg" class="img-fluid rounded-circle" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb"></i> <strong>Sfaturi:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Folosiți o fotografie profesională</li>
                                <li>Imaginile pătrate funcționează cel mai bine</li>
                                <li>Dimensiune minimă recomandată: 300x300px</li>
                            </ul>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="mt-4">
                <?= $this->Form->button(__('Salvează membru personal'), [
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="photo_file"]');
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
