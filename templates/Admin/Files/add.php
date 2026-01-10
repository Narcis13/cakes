<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File $file
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus"></i> Încarcă fișier</h2>
    <?= $this->Html->link(
        '<i class="fas fa-arrow-left"></i> Înapoi la fișiere',
        ['action' => 'index'],
        ['class' => 'btn btn-secondary', 'escape' => false]
    ) ?>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->Form->create($file, [
            'type' => 'file',
            'class' => 'needs-validation',
            'novalidate' => true
        ]) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <label class="form-label">Selectează fișier *</label>
                    <?= $this->Form->control('file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'required' => true,
                        'label' => false,
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar'
                    ]) ?>
                    <div class="form-text">
                        Tipuri de fișiere permise: PDF, documente Word, foi de calcul Excel, prezentări PowerPoint,
                        fișiere text, imagini și arhive. Dimensiune maximă: 10MB.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('description', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Descriere'],
                        'placeholder' => 'Descriere scurtă a fișierului...',
                        'rows' => 3
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <?= $this->Form->control('category', [
                        'class' => 'form-control',
                        'label' => ['class' => 'form-label', 'text' => 'Categorie'],
                        'placeholder' => 'ex: Documente, Rapoarte, Broșuri'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Vizibilitate</label>
                    <div class="form-check form-switch">
                        <?= $this->Form->control('is_public', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => ['class' => 'form-check-label', 'text' => 'Fișier accesibil public'],
                            'checked' => true
                        ]) ?>
                    </div>
                    <div class="form-text">
                        Fișierele publice pot fi accesate direct prin URL. Fișierele private necesită autentificare.
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?= $this->Html->link(
                'Anulează',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary me-md-2']
            ) ?>
            <?= $this->Form->button(
                '<i class="fas fa-upload"></i> Încarcă fișier',
                ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
            ) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File preview and validation
    const fileInput = document.querySelector('input[type="file"]');
    const form = document.querySelector('form');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size
            if (file.size > 10 * 1024 * 1024) {
                alert('Dimensiunea fișierului depășește limita de 10MB.');
                this.value = '';
                return;
            }

            // Auto-populate category based on file type
            const categoryInput = document.querySelector('input[name="category"]');
            if (!categoryInput.value) {
                const fileName = file.name.toLowerCase();
                if (fileName.includes('.pdf')) {
                    categoryInput.value = 'Documente';
                } else if (fileName.includes('.jpg') || fileName.includes('.png') || fileName.includes('.gif')) {
                    categoryInput.value = 'Imagini';
                } else if (fileName.includes('.doc') || fileName.includes('.docx')) {
                    categoryInput.value = 'Documente';
                } else if (fileName.includes('.xls') || fileName.includes('.xlsx')) {
                    categoryInput.value = 'Foi de calcul';
                } else if (fileName.includes('.ppt') || fileName.includes('.pptx')) {
                    categoryInput.value = 'Prezentări';
                }
            }
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Vă rugăm să selectați un fișier pentru încărcare.');
            return false;
        }
    });
});
</script>
