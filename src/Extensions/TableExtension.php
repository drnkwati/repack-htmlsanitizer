<?php

namespace Repack\HtmlSanitizer\Extensions;

use Repack\HtmlSanitizer\AspectExtension;

class TableExtension implements AspectExtension
{
    public function getName()
    {
        return 'table';
    }

    public function createNodeVisitors(array $config = array())
    {
        $config['tags_allowed'] = array(
            'table',
            'tbody',
            'thead',
            'tfoot',
            'th',
            'tr',
            'td',
            'caption',
            'col',
            'colgroup',
        );

        return parent::createNodeVisitors($config);
    }
}
