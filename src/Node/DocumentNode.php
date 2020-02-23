<?php

namespace Repack\HtmlSanitizer\Node;

use Repack\HtmlSanitizer\AspectNode;

class DocumentNode extends AbstractString implements AspectNode
{
    /**
     * @var AspectNode[]
     */
    private $children = array();

    /**
     * @param AspectNode $child
     */
    public function addChild(AspectNode $child)
    {
        $this->children[] = $child;
    }

    public function render()
    {
        return $this->renderChildren();
    }

    public function getParent()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function renderChildren()
    {
        $rendered = '';
        foreach ($this->children as $child) {
            $rendered .= $child->render();
        }

        return $rendered;
    }
}
