<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\File $file
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-alt"></i> Detalii fișier</h2>
    <div>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Înapoi la fișiere',
            ['action' => 'index'],
            ['class' => 'btn btn-secondary me-2', 'escape' => false]
        ) ?>
        <?= $this->Html->link(
            '<i class="fas fa-edit"></i> Editează',
            ['action' => 'edit', $file->id],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="<?= $this->element('file_icon', ['file_type' => $file->file_type]) ?> me-2"></i>
                    <?= h($file->original_name) ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($file->description): ?>
                    <h6>Descriere</h6>
                    <p class="text-muted"><?= h($file->description) ?></p>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Informații fișier</h6>
                        <table class="table table-sm">
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
                            <?php if ($file->category): ?>
                            <tr>
                                <th>Categorie:</th>
                                <td><span class="badge bg-secondary"><?= h($file->category) ?></span></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Statistici</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Descărcări:</th>
                                <td><span class="badge bg-info"><?= $this->Number->format($file->download_count) ?></span></td>
                            </tr>
                            <tr>
                                <th>Vizibilitate:</th>
                                <td>
                                    <?php if ($file->is_public): ?>
                                        <span class="badge bg-success">Public</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Privat</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Încărcat:</th>
                                <td><?= h($file->created->format('j M Y H:i')) ?></td>
                            </tr>
                            <tr>
                                <th>Modificat:</th>
                                <td><?= h($file->modified->format('j M Y H:i')) ?></td>
                            </tr>
                            <?php if ($file->user): ?>
                            <tr>
                                <th>Încărcat de:</th>
                                <td><?= h($file->user->email) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    <h6>URL fișier</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="fileUrl"
                               value="<?= $this->Url->build($file->file_url, ['fullBase' => true]) ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyUrlBtn">
                            <i class="fas fa-copy"></i> Copiază
                        </button>
                    </div>
                    <div class="form-text">Link direct către fișier</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acțiuni</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?= $this->Html->link(
                        '<i class="fas fa-download"></i> Descarcă fișier',
                        ['action' => 'download', $file->id],
                        ['class' => 'btn btn-success', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<i class="fas fa-edit"></i> Editează detalii',
                        ['action' => 'edit', $file->id],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash"></i> Șterge fișier',
                        ['action' => 'delete', $file->id],
                        [
                            'confirm' => __('Sigur doriți să ștergeți "{0}"?', $file->original_name),
                            'class' => 'btn btn-outline-danger',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>
        </div>

        <!-- Preview for images -->
        <?php if ($file->file_type === 'image'): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Previzualizare</h5>
            </div>
            <div class="card-body text-center">
                <img src="<?= h($file->file_url) ?>"
                     alt="<?= h($file->original_name) ?>"
                     class="img-fluid rounded"
                     style="max-height: 300px;">
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('copyUrlBtn').addEventListener('click', function() {
        const urlInput = document.getElementById('fileUrl');
        urlInput.select();
        navigator.clipboard.writeText(urlInput.value).then(function() {
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
