<?php

namespace Repack\HtmlSanitizer;

interface AspectNode
{
    /**
     * Return this node's parent node if it has one.
     *
     * @return AspectNode|null
     */
    public function getParent();

    /**
     * Add a child to this node.
     *
     * @param AspectNode $node
     */
    public function addChild(AspectNode $node);

    /**
     * Render this node as a string.
     *
     * @return string
     */
    public function render();
}
