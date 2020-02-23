<?php

namespace Repack\HtmlSanitizer;

use DOMNode;

/**
 * A visitor visit supported DOM nodes to decide whether and how to include them in the final output.
 *
 * @author Steve Nebes <snebes@gmail.com>
 */
interface AspectNodeVisitor
{
    /**
     * @return array
     */
    public function getSupportedNodeNames();

    /**
     * Whether this visitor supports the DOM node or not in the current context.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return bool
     */
    public function supports(DOMNode $domNode, Cursor $cursor);

    /**
     * Enter the DOM node.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function enterNode(DOMNode $domNode, Cursor $cursor);

    /**
     * Leave the DOM node.
     *
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function leaveNode(DOMNode $domNode, Cursor $cursor);
}
