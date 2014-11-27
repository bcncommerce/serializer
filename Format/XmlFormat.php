<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format;

use Bcn\Component\Serializer\Definition;
use Bcn\Component\Serializer\Format\Parser\Handler\Context\RootContext;
use Bcn\Component\Serializer\Format\Writer\XmlWriter;
use Bcn\Component\Serializer\Format\Parser\XmlParser;
use Bcn\Component\Serializer\Format\Parser\Handler\ContextHandler;
use Bcn\Component\Serializer\Format\Parser\Handler\Context\Context;

class XmlFormat implements FormatInterface
{
    /** @var XmlWriter */
    protected $writer;

    /** @var XmlParser */
    protected $parser;

    /**
     * @param XmlParser $parser
     * @param XmlWriter $writer
     */
    public function __construct(XmlParser $parser = null, XmlWriter $writer = null)
    {
        $this->writer = $writer ?: new XmlWriter();
        $this->parser = $parser ?: new XmlParser();
    }

    /**
     * @param mixed      $origin
     * @param Definition $definition
     * @param resource   $stream
     */
    public function encode($origin, Definition $definition, $stream)
    {
        $this->writer->write($stream, $origin, $definition);
    }

    /**
     * @param  resource   $stream
     * @param  Definition $definition
     * @param  mixed      $origin
     * @return mixed
     */
    public function decode($stream, Definition $definition, &$origin = null)
    {
        $context = new RootContext($origin, $definition);
        $handler = new ContextHandler($context);

        $this->parser->parse($stream, $handler);

        return $context->fetch();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'xml';
    }
}
