<?php

namespace Repack\HtmlSanitizer\Parsers;

use DOMNode;
use Exception;
use Masterminds\HTML5;
use Repack\HtmlSanitizer\AspectParser;
use Repack\HtmlSanitizer\Exceptions\ParsingFailedException;

class MastermindsParser implements AspectParser
{
    /**
     * @param string $html
     *
     * @return DOMNode
     */
    public function parse($html)
    {
        try {
            $parser = new HTML5();

            return $parser->loadHTMLFragment($html);
        } catch (Exception $t) {
            throw new ParsingFailedException($this, $t);
        }
    }
}
