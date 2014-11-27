<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Writer;

use Bcn\Component\Serializer\Format\Writer\XmlWriter;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\StreamWrapper\Stream;

class XmlWriterTest extends TestCase
{
    public function testWriteScalar()
    {
        $origin = new \stdClass();

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('getNodeName')
            ->will($this->returnValue('scalar'));
        $definition->expects($this->once())
            ->method('extract')
            ->with($origin)
            ->will($this->returnValue('foo'));

        $stream = $this->getDataStream();

        $writer = new XmlWriter();
        $writer->write($stream, $origin, $definition);

        $this->assertXmlStringEqualsXmlString("<scalar>foo</scalar>", $this->getStreamContent($stream));
    }

    public function testWriteArray()
    {
        $origin = new \stdClass();

        $prototype = $this->getDefinitionMock();
        $prototype->expects($this->atLeastOnce())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $prototype->expects($this->atLeastOnce())
            ->method('getNodeName')
            ->will($this->returnValue('item'));
        $prototype->expects($this->atLeastOnce())
            ->method('extract')
            ->will($this->returnCallback(function ($value) {
                return strlen($value);
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isArray')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('getNodeName')
            ->will($this->returnValue('array'));
        $definition->expects($this->once())
            ->method('getPrototype')
            ->will($this->returnValue($prototype));
        $definition->expects($this->once())
            ->method('extract')
            ->with($origin)
            ->will($this->returnValue(array('one', 'two', 'three')));

        $stream = $this->getDataStream();

        $writer = new XmlWriter();
        $writer->write($stream, $origin, $definition);

        $this->assertXmlStringEqualsXmlString(
            "<array><item>3</item><item>3</item><item>5</item></array>",
            $this->getStreamContent($stream)
        );
    }

    public function testWriteObject()
    {
        $origin = new \stdClass();

        $prototype = $this->getDefinitionMock();
        $prototype->expects($this->atLeastOnce())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $prototype->expects($this->atLeastOnce())
            ->method('getNodeName')
            ->will($this->returnValue('name'));
        $prototype->expects($this->atLeastOnce())
            ->method('extract')
            ->will($this->returnCallback(function ($value) {
                return $value->getName();
            }));

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isObject')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('getNodeName')
            ->will($this->returnValue('object'));
        $definition->expects($this->once())
            ->method('getProperties')
            ->will($this->returnValue(array('name' => $prototype)));
        $definition->expects($this->once())
            ->method('extract')
            ->with($origin)
            ->will($this->returnValue($this->getDocument('one')));

        $stream = $this->getDataStream();

        $writer = new XmlWriter();
        $writer->write($stream, $origin, $definition);

        $this->assertXmlStringEqualsXmlString(
            "<object><name>Test name one</name></object>",
            $this->getStreamContent($stream)
        );
    }

    public function testWriteToUri()
    {
        $origin = new \stdClass();

        $definition = $this->getDefinitionMock();
        $definition->expects($this->once())
            ->method('isScalar')
            ->will($this->returnValue(true));
        $definition->expects($this->once())
            ->method('getNodeName')
            ->will($this->returnValue('scalar'));
        $definition->expects($this->once())
            ->method('extract')
            ->with($origin)
            ->will($this->returnValue('foo'));

        $stream = new Stream();

        $writer = new XmlWriter();
        $writer->write($stream->getUri(), $origin, $definition);

        $this->assertXmlStringEqualsXmlString("<scalar>foo</scalar>", $stream->getContent());
    }
}
