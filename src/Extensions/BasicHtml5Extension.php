<?php

namespace Repack\HtmlSanitizer\Extensions;

class BasicHtml5Extension extends HTML5Extension
{
    public function getName()
    {
        return 'basicHtml5';
    }

    public function createNodeVisitors(array $config = array())
    {
        $config['tags_disallowed'] = array(
            // Main root
            'html',

            // Document metadata
            'base', 'head', 'link', 'meta', 'style', 'title',

            // Sectioning root
            'body',

            // Content sectioning

            // Text content

            // Inline text semantics

            // Image and multimedia

            // Embedded content
            'applet', 'embed', 'iframe', 'noembed', 'object', 'param', 'picture', 'source',

            // Scripting
            'canvas', 'noscript', 'script',

            // Demarcating edits
            'del', 'ins',

            // Table content

            // Forms
            'button', 'datalist', 'fieldset', 'form', 'input', 'label', 'legend',
            'meter', 'optgroup', 'option', 'output', 'progress', 'select', 'textarea',

            // Interactive elements
            'details', 'dialog', 'menu', 'menuitem', 'summary',

            // Web Components
            'content', 'element', 'shadow', 'slot', 'template',
        );

        return parent::createNodeVisitors($config);
    }
}
