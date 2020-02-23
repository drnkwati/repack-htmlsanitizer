<?php

namespace Repack\HtmlSanitizer\Extensions;

use Repack\HtmlSanitizer\AspectExtension;

class BasicExtension implements AspectExtension
{
    public function getName()
    {
        return 'basic';
    }

    public function createNodeVisitors(array $config = array())
    {
        $config['tags_allowed'] = array(
            'a',
            'br',
            'em',
            'i',
            'q',
            'small',
            'span',
            'strong',
            'sub',
            'sup',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'p',
            'figcaption',
            'figure',
            'blockquote',
            'del',
            'div',
        );

        return parent::createNodeVisitors($config);
    }
}
