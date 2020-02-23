<?php

namespace Repack\HtmlSanitizer\NodeVisitor;

use DOMNode;
use Repack\HtmlSanitizer\AspectNodeTag;
use Repack\HtmlSanitizer\AspectNodeVisitor;
use Repack\HtmlSanitizer\AspectOptions;
use Repack\HtmlSanitizer\Cursor;
use Repack\HtmlSanitizer\Node\TagNode;
use Repack\HtmlSanitizer\OptionsResolver;

class TagNodeVisitor implements AspectNodeVisitor
{
    /**
     * @var string
     */
    protected $qName;

    /**
     * @var array
     */
    protected $config;

    /**
     * Default values.
     *
     * @param string $qName
     * @param array  $options
     */
    public function __construct($qName, array $options = array())
    {
        $this->qName = $qName;
        $this->config = $this->configureOptions($options);
    }

    /**
     * @return string
     */
    public function getDomNodeName()
    {
        return $this->qName;
    }

    /**
     * @return array
     */
    public function getSupportedNodeNames()
    {
        $supported = array($this->getDomNodeName());

        $additional = isset($this->config['convert_elements']) ? $this->config['convert_elements'] : array();

        if (\is_array($additional)) {
            $supported = \array_merge($supported, $additional);
        }

        return $supported;
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return bool
     */
    public function supports(DOMNode $domNode, Cursor $cursor)
    {
        return \in_array($domNode->nodeName, $this->getSupportedNodeNames(), true);
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return AspectNodeTag
     */
    public function createNode(DOMNode $domNode, Cursor $cursor)
    {
        $node = new TagNode($cursor->node, $this->qName, array(), $this->config['childless']);

        $this->setAttributes($domNode, $node);

        return $node;
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function enterNode(DOMNode $domNode, Cursor $cursor)
    {
        $node = $this->createNode($domNode, $cursor);
        $cursor->node->addChild($node);

        if (true !== $this->config['childless']) {
            $cursor->node = $node;
        }
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function leaveNode(DOMNode $domNode, Cursor $cursor)
    {
        if (true !== $this->config['childless']) {
            $cursor->node = $cursor->node->getParent();
        }
    }

    /**
     * Read the value of a DOMNode attribute.
     *
     * @param DOMNode $domNode
     * @param string  $name
     *
     * @return null|string
     */
    protected function getAttribute(DOMNode $domNode, $name)
    {
        if (!\count($domNode->attributes)) {
            return null;
        }

        /** @var \DOMAttr $attribute */
        foreach ($domNode->attributes as $attribute) {
            if ($attribute->name === $name) {
                return $attribute->value;
            }
        }

        return null;
    }

    /**
     * Set attributes from a DOM node to a sanitized node.
     *
     * @param DOMNode          $domNode
     * @param AspectNodeTag $node
     */
    protected function setAttributes(DOMNode $domNode, AspectNodeTag $node)
    {
        // No attributes to worry about.
        if (!\count($domNode->attributes)) {
            return;
        }

        // No attributes allowed (empty array).
        $allowed = $this->config['allowed_attributes'];

        if (\is_array($allowed) && 0 === \count($allowed)) {
            return;
        }

        /** @var \DOMAttr $attribute */
        foreach ($domNode->attributes as $attribute) {
            $name = \mb_strtolower($attribute->name);

            if (
                (null === $allowed || \in_array($name, $allowed, true)) &&
                !\in_array($name, $this->config['blocked_attributes'], true)
            ) {
                if ('class' !== $name) {
                    $node->setAttribute($name, $attribute->value);
                } else {
                    $value = $this->filterClasses($attribute->value);

                    if (!empty($value)) {
                        $node->setAttribute($name, $value);
                    }
                }
            }
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function filterClasses($value)
    {
        if (empty($value)) {
            return '';
        }

        // No class allowed (empty array).
        $allowed = $this->config['allowed_classes'];

        if (\is_array($allowed) && 0 === \count($allowed)) {
            return '';
        }

        // Check them.
        $valid = array();
        $classes = \preg_split('/[\s]+/', $value) ?: array();

        foreach ($classes as $class) {
            if (
                (null === $allowed || \in_array($class, $allowed, true)) &&
                !\in_array($class, $this->config['blocked_classes'], true)
            ) {
                $valid[] = $class;
            }
        }

        return \implode(' ', $valid);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function configureOptions(array $options)
    {
        $resolver = new OptionsResolver;
        $resolver->setDefaults(array(
            'allowed_attributes' => null,
            'allowed_classes' => null,
            'blocked_attributes' => array(),
            'blocked_classes' => array(),
            'childless' => false,
            'convert_elements' => array(),
        ));

        $resolver->setAllowedTypes('allowed_attributes', array('null', 'array', 'string'));
        $resolver->setAllowedTypes('allowed_classes', array('null', 'array', 'string'));
        $resolver->setAllowedTypes('blocked_attributes', array('array', 'string'));
        $resolver->setAllowedTypes('blocked_classes', array('array', 'string'));
        $resolver->setAllowedTypes('childless', array('bool'));
        $resolver->setAllowedTypes('convert_elements', array('array', 'string'));

        $stringToArrayNormalizer = function (AspectOptions $options, $value) {
            if (\is_string($value)) {
                $value = \preg_split('/[\s]+/', $value, \PREG_SPLIT_NO_EMPTY) ?: array();
            }

            return $value;
        };

        $resolver->setNormalizer('allowed_attributes', $stringToArrayNormalizer);
        $resolver->setNormalizer('allowed_classes', $stringToArrayNormalizer);
        $resolver->setNormalizer('blocked_attributes', $stringToArrayNormalizer);
        $resolver->setNormalizer('blocked_classes', $stringToArrayNormalizer);
        $resolver->setNormalizer('convert_elements', $stringToArrayNormalizer);

        return $resolver->resolve($options);
    }
}
