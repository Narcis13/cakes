<?php
$iconMap = [
    'pdf' => 'fas fa-file-pdf text-danger',
    'document' => 'fas fa-file-word text-primary',
    'spreadsheet' => 'fas fa-file-excel text-success',
    'presentation' => 'fas fa-file-powerpoint text-warning',
    'text' => 'fas fa-file-alt text-secondary',
    'image' => 'fas fa-file-image text-info',
    'archive' => 'fas fa-file-archive text-dark',
    'other' => 'fas fa-file text-muted'
];

echo $iconMap[$file_type] ?? $iconMap['other'];
?>
