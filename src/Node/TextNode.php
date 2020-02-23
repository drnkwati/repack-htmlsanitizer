<?php

namespace Repack\HtmlSanitizer\Node;

use Repack\HtmlSanitizer\AspectNode;

class TextNode extends AbstractString implements AspectNode
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var AspectNode
     */
    private $parent;

    /**
     * Default values.
     *
     * @param AspectNode $parent
     * @param string        $text
     */
    public function __construct(AspectNode $parent, $text)
    {
        $this->parent = $parent;

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->encodeHtmlEntities($this->text);
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param AspectNode $child
     */
    public function addChild(AspectNode $child)
    {
        //
    }
}
