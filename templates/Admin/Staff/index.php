<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Staff> $staff
 * @var array $departments
 * @var array $staffTypes
 */
?>
<?php $this->assign('title', 'Gestionare personal'); ?>

<div class="staff index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Gestionare personal') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Adaugă membru personal',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <!-- Filtre -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-3">
                    <?= $this->Form->control('department_id', [
                        'label' => 'Filtrează după departament',
                        'options' => ['' => 'Toate departamentele'] + $departments,
                        'value' => $this->request->getQuery('department_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $this->Form->control('staff_type', [
                        'label' => 'Tip personal',
                        'options' => ['' => 'Toate tipurile'] + $staffTypes,
                        'value' => $this->request->getQuery('staff_type'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('is_active', [
                        'label' => 'Status',
                        'options' => ['' => 'Toate', '1' => 'Activ', '0' => 'Inactiv'],
                        'value' => $this->request->getQuery('is_active'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <?= $this->Form->button('<i class="fas fa-filter"></i> Filtrează', [
                            'type' => 'submit',
                            'class' => 'btn btn-secondary',
                            'escapeTitle' => false
                        ]) ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i> Resetează',
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$staff->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('name', 'Membru personal') ?></th>
                                <th><?= $this->Paginator->sort('title', 'Titlu/Poziție') ?></th>
                                <th><?= $this->Paginator->sort('department_id', 'Departament') ?></th>
                                <th><?= $this->Paginator->sort('staff_type', 'Tip') ?></th>
                                <th>Contact</th>
                                <th><?= $this->Paginator->sort('years_experience', 'Experiență') ?></th>
                                <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                                <th class="actions"><?= __('Acțiuni') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff as $staffMember): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($staffMember->photo): ?>
                                            <img src="<?= $this->Url->build('/img/staff/' . $staffMember->photo) ?>"
                                                 class="rounded-circle me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 alt="<?= h($staffMember->name) ?>">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center text-white"
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <?= $this->Html->link(
                                                h($staffMember->name),
                                                ['action' => 'view', $staffMember->id],
                                                ['class' => 'fw-bold text-decoration-none']
                                            ) ?>
                                            <?php if ($staffMember->specialization_data ?? $staffMember->specialization): ?>
                                                <small class="text-muted d-block"><?= h($staffMember->specialization_data->name ?? $staffMember->specialization) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= h($staffMember->title ?: '-') ?>
                                </td>
                                <td>
                                    <?php if ($staffMember->department): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-building"></i> <?= h($staffMember->department->name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Nealocat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $typeLabels = [
                                        'doctor' => 'Medic',
                                        'nurse' => 'Asistent',
                                        'technician' => 'Tehnician',
                                        'administrator' => 'Administrator',
                                        'other' => 'Altul'
                                    ];
                                    ?>
                                    <span class="badge bg-primary">
                                        <?= h($typeLabels[$staffMember->staff_type] ?? ucfirst($staffMember->staff_type)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <?php if ($staffMember->phone): ?>
                                            <a href="tel:<?= h($staffMember->phone) ?>" class="text-decoration-none">
                                                <i class="fas fa-phone"></i> <?= h($staffMember->phone) ?>
                                            </a><br>
                                        <?php endif; ?>
                                        <?php if ($staffMember->email): ?>
                                            <a href="mailto:<?= h($staffMember->email) ?>" class="text-decoration-none">
                                                <i class="fas fa-envelope"></i> <?= h($staffMember->email) ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!$staffMember->phone && !$staffMember->email): ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($staffMember->years_experience): ?>
                                        <?= h($staffMember->years_experience) ?> ani
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($staffMember->is_active): ?>
                                        <span class="badge bg-success">Activ</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactiv</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Vizualizează']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Editează']
                                    ) ?>
                                    <?php if ($staffMember->email): ?>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info mail-btn"
                                                data-staff-id="<?= $staffMember->id ?>"
                                                data-staff-name="<?= h($staffMember->name) ?>"
                                                data-staff-email="<?= h($staffMember->email) ?>"
                                                title="Trimite email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-power-off"></i>',
                                        ['action' => 'toggleActive', $staffMember->id],
                                        [
                                            'confirm' => $staffMember->is_active
                                                ? __('Sunteți sigur că doriți să dezactivați "{0}"?', $staffMember->name)
                                                : __('Sunteți sigur că doriți să activați "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-warning',
                                            'escape' => false,
                                            'title' => $staffMember->is_active ? 'Dezactivează' : 'Activează'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $staffMember->id],
                                        [
                                            'confirm' => __('Sunteți sigur că doriți să ștergeți "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Șterge'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="paginator mt-3">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('prima')) ?>
                        <?= $this->Paginator->prev('< ' . __('anterioara')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('următoarea') . ' >') ?>
                        <?= $this->Paginator->last(__('ultima') . ' >>') ?>
                    </ul>
                    <p class="text-muted"><?= $this->Paginator->counter(__('Pagina {{page}} din {{pages}}, afișând {{current}} înregistrare(i) din {{count}} total')) ?></p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nu s-au găsit membri ai personalului</h5>
                    <p class="text-muted">Adăugați primul membru al personalului pentru a începe.</p>
                    <?= $this->Html->link(
                        'Adaugă membru personal',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal dialog pentru email -->
<div class="modal fade" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mailModalLabel">
                    <i class="fas fa-envelope"></i> Trimite email către <span id="staffNameModal"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Închide"></button>
            </div>
            <div class="modal-body">
                <form id="mailForm">
                    <input type="hidden" id="staffId" name="staff_id">
                    <input type="hidden" id="staffEmail" name="staff_email">

                    <div class="mb-3">
                        <label for="mailSubject" class="form-label">Subiect</label>
                        <input type="text" class="form-control" id="mailSubject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="mailContent" class="form-label">Conținut mesaj</label>
                        <textarea class="form-control" id="mailContent" name="content" rows="6" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                <button type="button" class="btn btn-primary" id="sendMailBtn">
                    <i class="fas fa-paper-plane"></i> Trimite
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mailModal = new bootstrap.Modal(document.getElementById('mailModal'));
    const mailForm = document.getElementById('mailForm');
    const sendMailBtn = document.getElementById('sendMailBtn');
    const csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';

    // Gestionează click-urile pe butonul de email
    document.querySelectorAll('.mail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.dataset.staffId;
            const staffName = this.dataset.staffName;
            const staffEmail = this.dataset.staffEmail;

            // Populează modalul cu datele personalului
            document.getElementById('staffId').value = staffId;
            document.getElementById('staffEmail').value = staffEmail;
            document.getElementById('staffNameModal').textContent = staffName;

            // Șterge câmpurile formularului
            document.getElementById('mailSubject').value = '';
            document.getElementById('mailContent').value = '';

            // Afișează modalul
            mailModal.show();
        });
    });

    // Gestionează click-ul pe butonul de trimitere
    sendMailBtn.addEventListener('click', function() {
        const staffId = document.getElementById('staffId').value;
        const staffEmail = document.getElementById('staffEmail').value;
        const subject = document.getElementById('mailSubject').value.trim();
        const content = document.getElementById('mailContent').value.trim();

        // Validare de bază
        if (!subject || !content) {
            alert('Vă rugăm să completați atât subiectul cât și conținutul.');
            return;
        }

        // Dezactivează butonul de trimitere în timpul trimiterii
        sendMailBtn.disabled = true;
        sendMailBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Se trimite...';

        // Pregătește datele pentru trimitere
        const data = {
            staff_id: staffId,
            staff_email: staffEmail,
            subject: subject,
            content: content
        };

        // Trimite email-ul prin AJAX
        fetch('<?= $this->Url->build(['action' => 'sendEmail']) ?>', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Eroare server: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Email-ul a fost trimis cu succes!');
                mailModal.hide();
            } else {
                alert('Trimiterea email-ului a eșuat: ' + (data.message || 'Eroare necunoscută'));
            }
        })
        .catch(error => {
            console.error('Eroare:', error);
            alert('Trimiterea email-ului a eșuat. Vă rugăm să încercați din nou.');
        })
        .finally(() => {
            // Reactivează butonul de trimitere
            sendMailBtn.disabled = false;
            sendMailBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Trimite';
        });
    });
});
</script>
