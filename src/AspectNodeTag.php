<?php

namespace Repack\HtmlSanitizer;

interface AspectNodeTag extends AspectNode
{
    /**
     * Return the value of this node given attribute.
     * Return null if the attribute does not exist.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute($name);

    /**
     * Set the value of this node given attribute.
     *
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value);
}
