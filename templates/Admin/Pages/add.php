<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<?php $this->assign('title', 'Add Page'); ?>

<div class="pages form content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Add Page') ?></h3>
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Back to Pages',
            ['action' => 'index'],
            ['class' => 'btn btn-outline-secondary', 'escape' => false]
        ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $this->Form->create($page) ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <?= $this->Form->control('title', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('slug', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'help' => 'Leave blank to auto-generate from title'
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('content', [
                                'type' => 'textarea',
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label', 'text' => 'Page Content (Optional)'],
                                'rows' => 6,
                                'help' => 'This is optional. You can build your page using components instead.'
                            ]) ?>
                        </div>
                        
                        <div class="mb-3">
                            <?= $this->Form->control('meta_description', [
                                'class' => 'form-control',
                                'label' => ['class' => 'form-label'],
                                'help' => 'SEO meta description (max 160 characters)'
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Publishing</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <?= $this->Form->checkbox('is_published', [
                                            'class' => 'form-check-input',
                                            'checked' => true
                                        ]) ?>
                                        <?= $this->Form->label('is_published', 'Published', [
                                            'class' => 'form-check-label'
                                        ]) ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <?= $this->Form->control('template', [
                                        'class' => 'form-control',
                                        'label' => ['class' => 'form-label'],
                                        'empty' => 'Default Template',
                                        'help' => 'Custom template file (optional)'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <?= $this->Form->button('Save Page', [
                        'class' => 'btn btn-primary'
                    ]) ?>
                    <?= $this->Html->link(
                        'Cancel',
                        ['action' => 'index'],
                        ['class' => 'btn btn-secondary ms-2']
                    ) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });
});
</script>
