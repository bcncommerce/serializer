<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Parser;

use Bcn\Component\Serializer\Format\Parser\XmlParser;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\StreamWrapper\Stream;

class XmlParserTest extends TestCase
{
    public function testParseSimpleDocument()
    {
        $handler = $this->getParserHandlerMock();
        $handler->expects($this->once())->method('start')->with('root', array());
        $handler->expects($this->once())->method('append')->with('content');
        $handler->expects($this->once())->method('end')->with('root');

        $stream = $this->getDataStream("<root>content</root>");

        $parser = new XmlParser();
        $parser->parse($stream, $handler);
    }

    public function testParseSimpleDocumentFromUri()
    {
        $handler = $this->getParserHandlerMock();
        $handler->expects($this->once())->method('start')->with('root', array());
        $handler->expects($this->once())->method('append')->with('content');
        $handler->expects($this->once())->method('end')->with('root');

        $stream = new Stream("<root>content</root>");

        $parser = new XmlParser();
        $parser->parse($stream->getUri(), $handler);
    }
}
