<?php
$colorMap = [
    'pdf' => 'danger',
    'document' => 'primary',
    'spreadsheet' => 'success',
    'presentation' => 'warning',
    'text' => 'secondary',
    'image' => 'info',
    'archive' => 'dark',
    'other' => 'light'
];

echo $colorMap[$file_type] ?? $colorMap['other'];
?>
