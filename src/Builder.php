<?php

namespace Repack\HtmlSanitizer;

use Psr\Log\LoggerInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class Builder
{
    /**
     * @var AspectExtension[]
     */
    private $extensions = array();

    /**
     * @var AspectParser|null
     */
    private $parser;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param AspectExtension $extension
     */
    public function registerExtension(AspectExtension $extension)
    {
        $this->extensions[$extension->getName()] = $extension;
    }

    /**
     * @param AspectParser|null $parser
     */
    public function setParser(AspectParser $parser = null)
    {
        $this->parser = $parser;
    }

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $config
     *
     * @return Sanitizer
     */
    public function build(array $config)
    {
        $nodeVisitors = array();

        foreach (isset($config['extensions']) ? $config['extensions'] : array() as $extensionName) {
            if (!isset($this->extensions[$extensionName])) {
                throw new \InvalidArgumentException(\sprintf(
                    'You have requested a non-existent sanitizer extension "%s" (available extensions: %s)',
                    $extensionName,
                    \implode(', ', \array_keys($this->extensions))
                ));
            }

            foreach ($this->extensions[$extensionName]->createNodeVisitors($config) as $tagName => $visitor) {
                $nodeVisitors[$tagName] = $visitor;
            }
        }

        return new Sanitizer(
            new DomVisitor($nodeVisitors),
            isset($config['max_input_length']) ? $config['max_input_length'] : 4294967295,
            $this->parser,
            $this->logger
        );
    }
}
