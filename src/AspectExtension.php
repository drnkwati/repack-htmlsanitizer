<?php

namespace Repack\HtmlSanitizer;

interface AspectExtension
{
    /**
     * Return this extension name, which will be used in the sanitizer configuration.
     */
    public function getName();

    /**
     * Return a list of node visitors to register in the sanitizer following the format tagName => visitor.
     * For instance: 'strong' => new StrongVisitor($config).
     *
     * @param array $config The configuration given by the user of the library.
     *
     * @return AspectNodeVisitor[]
     */
    public function createNodeVisitors(array $config = array());
}
