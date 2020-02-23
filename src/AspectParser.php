<?php

namespace Repack\HtmlSanitizer;

/**
 * A parser transforms a HTML string into a tree of DOMNode objects.
 */
interface AspectParser
{
    /**
     * Parse a given string and returns a DOMNode tree.
     * This method must throw a ParsingFailedException if parsing failed in order for
     * the sanitizer to catch it and return an empty string.
     *
     * @param string $html
     *
     * @return \DOMNode
     *
     * @throws ExceptionParsingFailed When the parsing fails.
     */
    public function parse($html);
}
