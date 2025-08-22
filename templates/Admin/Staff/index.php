<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Staff> $staff
 * @var array $departments
 * @var array $staffTypes
 */
?>
<?php $this->assign('title', 'Staff Management'); ?>

<div class="staff index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Staff Management') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-plus"></i> Add Staff Member',
            ['action' => 'add'],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-3">
                    <?= $this->Form->control('department_id', [
                        'label' => 'Filter by Department',
                        'options' => ['' => 'All Departments'] + $departments,
                        'value' => $this->request->getQuery('department_id'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $this->Form->control('staff_type', [
                        'label' => 'Staff Type',
                        'options' => ['' => 'All Types'] + $staffTypes,
                        'value' => $this->request->getQuery('staff_type'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $this->Form->control('is_active', [
                        'label' => 'Status',
                        'options' => ['' => 'All', '1' => 'Active', '0' => 'Inactive'],
                        'value' => $this->request->getQuery('is_active'),
                        'class' => 'form-select'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <?= $this->Form->button('<i class="fas fa-filter"></i> Filter', [
                            'type' => 'submit',
                            'class' => 'btn btn-secondary',
                            'escape' => false
                        ]) ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i> Clear', 
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
                                <th><?= $this->Paginator->sort('name', 'Staff Member') ?></th>
                                <th><?= $this->Paginator->sort('title', 'Title/Position') ?></th>
                                <th><?= $this->Paginator->sort('department_id', 'Department') ?></th>
                                <th><?= $this->Paginator->sort('staff_type', 'Type') ?></th>
                                <th>Contact</th>
                                <th><?= $this->Paginator->sort('years_experience', 'Experience') ?></th>
                                <th><?= $this->Paginator->sort('is_active', 'Status') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
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
                                        <span class="text-muted">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= h(ucfirst($staffMember->staff_type)) ?>
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
                                        <?= h($staffMember->years_experience) ?> years
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($staffMember->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'view', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'View']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'edit', $staffMember->id],
                                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false, 'title' => 'Edit']
                                    ) ?>
                                    <?php if ($staffMember->email): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info mail-btn" 
                                                data-staff-id="<?= $staffMember->id ?>"
                                                data-staff-name="<?= h($staffMember->name) ?>"
                                                data-staff-email="<?= h($staffMember->email) ?>"
                                                title="Send Email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-power-off"></i>',
                                        ['action' => 'toggleActive', $staffMember->id],
                                        [
                                            'confirm' => $staffMember->is_active 
                                                ? __('Are you sure you want to deactivate "{0}"?', $staffMember->name)
                                                : __('Are you sure you want to activate "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-warning',
                                            'escape' => false,
                                            'title' => $staffMember->is_active ? 'Deactivate' : 'Activate'
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'delete', $staffMember->id],
                                        [
                                            'confirm' => __('Are you sure you want to delete "{0}"?', $staffMember->name),
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Delete'
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
                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                    </ul>
                    <p class="text-muted"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No staff members found</h5>
                    <p class="text-muted">Add your first staff member to get started.</p>
                    <?= $this->Html->link(
                        'Add Staff Member',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mail Dialog Modal -->
<div class="modal fade" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mailModalLabel">
                    <i class="fas fa-envelope"></i> Send Email to <span id="staffNameModal"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mailForm">
                    <input type="hidden" id="staffId" name="staff_id">
                    <input type="hidden" id="staffEmail" name="staff_email">
                    
                    <div class="mb-3">
                        <label for="mailSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="mailSubject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mailContent" class="form-label">Message Content</label>
                        <textarea class="form-control" id="mailContent" name="content" rows="6" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendMailBtn">
                    <i class="fas fa-paper-plane"></i> Send
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
    
    // Handle mail button clicks
    document.querySelectorAll('.mail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.dataset.staffId;
            const staffName = this.dataset.staffName;
            const staffEmail = this.dataset.staffEmail;
            
            // Populate modal with staff data
            document.getElementById('staffId').value = staffId;
            document.getElementById('staffEmail').value = staffEmail;
            document.getElementById('staffNameModal').textContent = staffName;
            
            // Clear form fields
            document.getElementById('mailSubject').value = '';
            document.getElementById('mailContent').value = '';
            
            // Show modal
            mailModal.show();
        });
    });
    
    // Handle send button click
    sendMailBtn.addEventListener('click', function() {
        const form = mailForm;
        const formData = new FormData(form);
        
        // Basic validation
        if (!formData.get('subject').trim() || !formData.get('content').trim()) {
            alert('Please fill in both subject and content fields.');
            return;
        }
        
        // Disable send button while sending
        sendMailBtn.disabled = true;
        sendMailBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        // Send email via AJAX
        fetch('<?= $this->Url->build(['action' => 'sendEmail']) ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email sent successfully!');
                mailModal.hide();
            } else {
                alert('Failed to send email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send email. Please try again.');
        })
        .finally(() => {
            // Re-enable send button
            sendMailBtn.disabled = false;
            sendMailBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
        });
    });
});
</script>