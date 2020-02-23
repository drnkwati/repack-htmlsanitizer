<?php

namespace Repack\HtmlSanitizer\Exceptions;

use Repack\HtmlSanitizer\AspectParser;

class ParsingFailedException extends \InvalidArgumentException
{
    /**
     * @var AspectParser
     */
    private $parser;

    /**
     * Default values.
     *
     * @param AspectParser $parser
     * @param \Throwable|null $previous
     */
    public function __construct(AspectParser $parser, $previous = null)
    {
        parent::__construct('HTML parsing failed using parser ' . \get_class($parser), 0, $previous);

        $this->parser = $parser;
    }

    /**
     * @return AspectParser
     */
    public function getParser()
    {
        return $this->parser;
    }
}
