<?php
/**
 * TinyMCE Editor Element
 * 
 * @var \App\View\AppView $this
 * @var string $selector The CSS selector for the textarea (default: '.tinymce-editor')
 */

use Cake\Core\Configure;

$tinymceKey = Configure::read('ApiKeys.tinymce', 'no-api-key');
$selector = $selector ?? '.tinymce-editor';
?>

<script src="https://cdn.tiny.cloud/1/<?= h($tinymceKey) ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '<?= h($selector) ?>',
    height: 500,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    branding: false,
    promotion: false,
    setup: function(editor) {
        // Ensure content is saved back to textarea before form submission
        editor.on('submit', function() {
            editor.save();
        });
        
        // Update textarea on every change
        editor.on('change', function() {
            editor.save();
        });
    }
});
</script>