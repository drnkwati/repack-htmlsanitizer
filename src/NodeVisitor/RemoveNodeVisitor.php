<?php

namespace Repack\HtmlSanitizer\NodeVisitor;

use DOMNode;
use Repack\HtmlSanitizer\Cursor;

class RemoveNodeVisitor extends TagNodeVisitor
{
    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function enterNode(DOMNode $domNode, Cursor $cursor)
    {
        while ($domNode->hasChildNodes()) {
            $domNode->removeChild($domNode->childNodes[0]);
        }
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function leaveNode(DOMNode $domNode, Cursor $cursor)
    {}
}
