<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$alertClass = 'alert-info';
$icon = 'fa-info-circle';
if (!empty($params['class'])) {
    if (str_contains($params['class'], 'success')) {
        $alertClass = 'alert-success';
        $icon = 'fa-check-circle';
    } elseif (str_contains($params['class'], 'error') || str_contains($params['class'], 'danger')) {
        $alertClass = 'alert-danger';
        $icon = 'fa-exclamation-circle';
    } elseif (str_contains($params['class'], 'warning')) {
        $alertClass = 'alert-warning';
        $icon = 'fa-exclamation-triangle';
    }
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
    <i class="fas <?= $icon ?> me-2"></i><?= $message ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
