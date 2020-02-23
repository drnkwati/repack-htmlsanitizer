<?php

namespace Repack\HtmlSanitizer\Node;

use Repack\HtmlSanitizer\AspectNode;
use Repack\HtmlSanitizer\AspectNodeTag;

class TagNode extends DocumentNode implements AspectNodeTag
{
    /**
     * @var AspectNode
     */
    private $parent;

    /**
     * @var string
     */
    private $qName;

    /**
     * @var array<string, string|null>
     */
    private $attributes = array();

    /**
     * @var bool
     */
    private $isChildless = false;

    /**
     * Default values.
     *
     * @param AspectNode $parent
     * @param string        $qName
     * @param array         $attributes
     * @param bool          $isChildless
     */
    public function __construct(AspectNode $parent, $qName, array $attributes = array(), $isChildless = false)
    {
        $this->parent = $parent;

        $this->qName = $qName;
        $this->attributes = $attributes;
        $this->isChildless = $isChildless;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->qName;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param string      $name
     * @param string|null $value
     */
    public function setAttribute($name, $value)
    {
        // Always use only the first declaration (ease sanitization)
        if (!\array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $tag = $this->getTagName();

        if ($this->isChildless) {
            return '<' . $tag . $this->renderAttributes() . ' />';
        }

        return '<' . $tag . $this->renderAttributes() . '>' . $this->renderChildren() . '</' . $tag . '>';
    }

    /**
     * @return string
     */
    private function renderAttributes()
    {
        $rendered = array();

        foreach ($this->attributes as $name => $value) {
            if (null === $value) {
                // Tag should be removed as a sanitizer found suspect data inside
                continue;
            }

            $attr = $this->encodeHtmlEntities($name);
            if ('' !== $value) {
                // In quirks mode, IE8 does a poor job producing innerHTML values.
                // If JavaScript does:
                //      nodeA.innerHTML = nodeB.innerHTML;
                // and nodeB contains (or even if ` was encoded properly):
                //      <div attr="``foo=bar">
                // then IE8 will produce:
                //      <div attr=``foo=bar>
                // as the value of nodeB.innerHTML and assign it to nodeA.
                // IE8's HTML parser treats `` as a blank attribute value and foo=bar becomes a separate attribute.
                // Adding a space at the end of the attribute prevents this by forcing IE8 to put double
                // quotes around the attribute when computing nodeB.innerHTML.
                if (false !== \mb_strpos($value, '`')) {
                    $value .= ' ';
                }

                $attr .= '="' . $this->encodeHtmlEntities($value) . '"';
            }

            $rendered[] = $attr;
        }

        return $rendered ? ' ' . \implode(' ', $rendered) : '';
    }
}
