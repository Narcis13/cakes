<?php
/**
 * HTML Purifier Configuration
 *
 * This configuration defines which HTML tags and attributes are allowed
 * in user-generated content to prevent XSS attacks while allowing safe formatting.
 */

return [
    // HTML version to use
    'HTML.Doctype' => 'HTML 4.01 Transitional',

    // Allowed HTML tags
    // Safe tags for formatting content: paragraphs, line breaks, emphasis, lists, headers, links, images, tables
    'HTML.Allowed' => 'p,br,strong,em,b,i,u,ul,ol,li,h2,h3,h4,h5,h6,a[href|title|target],img[src|alt|width|height],table,thead,tbody,tr,th,td,blockquote,code,pre,span[class]',

    // Auto-paragraph feature
    'AutoFormat.AutoParagraph' => true,

    // Remove empty paragraphs
    'AutoFormat.RemoveEmpty' => true,

    // Convert line breaks to <br> tags
    'AutoFormat.Linkify' => true,

    // Security settings
    'URI.DisableExternalResources' => false,
    'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true],

    // Only allow specific attributes on <a> tags
    'Attr.AllowedFrameTargets' => ['_blank'],

    // Output settings
    'Output.TidyFormat' => true,

    // Character encoding
    'Core.Encoding' => 'UTF-8',

    // CSS settings (allow basic styling on span elements)
    'CSS.AllowedProperties' => 'color,background-color,font-weight,font-style,text-decoration',
];
