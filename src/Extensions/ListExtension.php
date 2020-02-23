<?php

namespace Repack\HtmlSanitizer\Extensions;

use Repack\HtmlSanitizer\AspectExtension;

class ListExtension implements AspectExtension
{
    public function getName()
    {
        return 'list';
    }

    public function createNodeVisitors(array $config = array())
    {
        $config['tags_allowed'] = array(
            'dd',
            'dl',
            'dt',
            'li',
            'ol',
            'ul',
        );

        return parent::createNodeVisitors($config);
    }
}
