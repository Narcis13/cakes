<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File $file
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit"></i> Editează fișier</h2>
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Înapoi la fișiere',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary me-2', 'escape' => false]
        ) ?>
        <?= $this->Form->postLink(
            '<i class="fas fa-trash"></i> Șterge',
            ['action' => 'delete', $file->id],
            [
                'confirm' => __('Sigur doriți să ștergeți "{0}"?', $file->original_name),
                'class' => 'btn btn-danger',
                'escape' => false
            ]
        ) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?= $this->Form->create($file, [
                    'type' => 'file',
                    'class' => 'needs-validation',
                    'novalidate' => true
                ]) ?>

                <div class="mb-4">
                    <label class="form-label">Înlocuiește fișier (opțional)</label>
                    <?= $this->Form->control('file', [
                        'type' => 'file',
                        'class' => 'form-control',
                        'label' => false,
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar'
                    ]) ?>
                    <div class="form-text">
                        Lăsați gol pentru a păstra fișierul curent. Dimensiune maximă: 10MB.
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

                <div class="mb-3">
                    <label class="form-label">Vizibilitate</label>
                    <div class="form-check form-switch">
                        <?= $this->Form->control('is_public', [
                            'type' => 'checkbox',
                            'class' => 'form-check-input',
                            'label' => ['class' => 'form-check-label', 'text' => 'Fișier accesibil public']
                        ]) ?>
                    </div>
                    <div class="form-text">
                        Fișierele publice pot fi accesate direct prin URL. Fișierele private necesită autentificare.
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <?= $this->Html->link(
                        'Anulează',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary me-md-2']
                    ) ?>
                    <?= $this->Form->button(
                        '<i class="fas fa-save"></i> Actualizează fișier',
                        ['type' => 'submit', 'class' => 'btn btn-primary', 'escapeTitle' => false]
                    ) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informații fișier</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Nume original:</th>
                        <td><?= h($file->original_name) ?></td>
                    </tr>
                    <tr>
                        <th>Tip fișier:</th>
                        <td>
                            <span class="badge bg-<?= $this->element('file_type_color', ['file_type' => $file->file_type]) ?>">
                                <?= h(ucfirst($file->file_type)) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dimensiune:</th>
                        <td><?= $this->Number->toReadableSize($file->file_size) ?></td>
                    </tr>
                    <tr>
                        <th>Tip MIME:</th>
                        <td><code><?= h($file->mime_type) ?></code></td>
                    </tr>
                    <tr>
                        <th>Descărcări:</th>
                        <td><?= $this->Number->format($file->download_count) ?></td>
                    </tr>
                    <tr>
                        <th>Încărcat:</th>
                        <td><?= h($file->created->format('j M Y H:i')) ?></td>
                    </tr>
                    <tr>
                        <th>Modificat:</th>
                        <td><?= h($file->modified->format('j M Y H:i')) ?></td>
                    </tr>
                </table>

                <div class="d-grid gap-2">
                    <?= $this->Html->link(
                        '<i class="fas fa-download"></i> Descarcă',
                        ['action' => 'download', $file->id],
                        ['class' => 'btn btn-success btn-sm', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<i class="fas fa-copy"></i> Copiază URL',
                        'javascript:void(0)',
                        [
                            'class' => 'btn btn-outline-secondary btn-sm copy-url',
                            'escape' => false,
                            'data-url' => $this->Url->build($file->file_url, ['fullBase' => true])
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File size validation
    const fileInput = document.querySelector('input[type="file"]');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.size > 10 * 1024 * 1024) {
            alert('Dimensiunea fișierului depășește limita de 10MB.');
            this.value = '';
        }
    });

    // Copy URL functionality
    document.querySelector('.copy-url').addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        navigator.clipboard.writeText(url).then(function() {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '1055';
            toast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong class="me-auto">Succes</strong>
                    </div>
                    <div class="toast-body">
                        URL copiat în clipboard!
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        });
    });
});
</script>
