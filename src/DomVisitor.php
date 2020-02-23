<?php

namespace Repack\HtmlSanitizer;

use Repack\HtmlSanitizer\Node\DocumentNode;
use Repack\HtmlSanitizer\Node\TextNode;

/**
 * The DomVisitor iterate over the parsed DOM tree and visit nodes using AspectNodeVisitor objects.
 * For performance reasons, these objects are split in 2 groups: generic ones and node-specific ones.
 */
class DomVisitor
{
    /**
     * @var AspectNodeVisitor[]
     */
    private $nodeVisitors = array();

    /**
     * @param AspectNodeVisitor[] $visitors
     */
    public function __construct(array $visitors = array())
    {
        foreach ($visitors as $visitor) {
            if ($visitor instanceof AspectNodeVisitor) {
                foreach ($visitor->getSupportedNodeNames() as $nodeName) {
                    $this->nodeVisitors[$nodeName][] = $visitor;
                }
            }
        }
    }

    /**
     * @param \DOMNode $node
     *
     * @return DocumentNode
     */
    public function visit(\DOMNode $node)
    {
        $cursor = new Cursor();
        $cursor->node = new DocumentNode();

        $this->visitNode($node, $cursor);

        return $cursor->node;
    }

    /**
     * @param \DOMNode $node
     * @param Cursor   $cursor
     */
    private function visitNode(\DOMNode $node, Cursor $cursor)
    {
        /** @var AspectNodeVisitor[] $supportedVisitors */
        $supportedVisitors = isset($this->nodeVisitors[$node->nodeName]) ? $this->nodeVisitors[$node->nodeName] : array();

        foreach ($supportedVisitors as $visitor) {
            if ($visitor->supports($node, $cursor)) {
                $visitor->enterNode($node, $cursor);
            }
        }

        /** @var \DOMNode $child */
        foreach (isset($node->childNodes) ? $node->childNodes : array() as $child) {
            if ('#text' === $child->nodeName) {
                // Add text in the safe tree without a visitor for performance
                $cursor->node->addChild(new TextNode($cursor->node, $child->nodeValue));
            } elseif (!$child instanceof \DOMText) {
                // Ignore comments for security reasons (interpreted differently by browsers)
                $this->visitNode($child, $cursor);
            }
        }

        foreach (\array_reverse($supportedVisitors) as $visitor) {
            if ($visitor->supports($node, $cursor)) {
                $visitor->leaveNode($node, $cursor);
            }
        }
    }
}
