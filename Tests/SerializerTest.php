<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Serializer;

class SerializerTest extends TestCase
{
    public function testSerialize()
    {
        $document = $this->getDocument();

        $encoder = $this->getEncoderMock();
        $encoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('name'), $this->equalTo('text'));
        $encoder->expects($this->at(1))
            ->method('write')
            ->with('Test name ');
        $encoder->expects($this->at(2))
            ->method('end')
            ->withAnyParameters();

        $nameSerializer = $this->getSerializerMock();
        $nameSerializer->expects($this->once())
            ->method('getNodeType')
            ->will($this->returnValue('text'));
        $nameSerializer->expects($this->once())
            ->method('serialize')
            ->with($this->anything(), $this->equalTo($encoder))
            ->will($this->returnCallback(function ($value) use ($encoder) {
                $encoder->write($value);
            }));

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', $nameSerializer);
        $serializer->serialize($document, $encoder);
    }

    public function testUnserialize()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('name'), $this->equalTo('text'))
            ->will($this->returnValue(true));
        $decoder->expects($this->at(1))
            ->method('read')
            ->will($this->returnValue('Name'));
        $decoder->expects($this->at(2))
            ->method('end');

        $nameSerializer = $this->getSerializerMock();
        $nameSerializer->expects($this->once())
            ->method('getNodeType')
            ->will($this->returnValue('text'));
        $nameSerializer->expects($this->once())
            ->method('unserialize')
            ->will($this->returnCallback(function (DecoderInterface $decoder) {
                return $decoder->read();
            }));

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', $nameSerializer);
        $document = $serializer->unserialize($decoder);

        $this->assertEquals('Name', $document->getName());
    }

    public function testUnserializeFactory()
    {
        $instance = $this->getDocument();
        $factory = function () use ($instance) {
            return $instance;
        };

        $serializer = new Serializer($factory);
        $document = $serializer->unserialize($this->getDecoderMock());

        $this->assertSame($instance, $document);
    }

    public function testUnserializeToObject()
    {
        $instance = $this->getDocument();
        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $document = $serializer->unserialize($this->getDecoderMock(), $instance);

        $this->assertSame($instance, $document);
    }

    public function testAddPropertyException()
    {
        $this->setExpectedException('Exception');

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', $this->getSerializerMock());
        $serializer->add('name', $this->getSerializerMock());
    }

    public function testRemoveProperty()
    {
        $serializer = new Serializer(self::DOCUMENT_CLASS);

        $serializer->add('name', $this->getSerializerMock());
        $this->assertTrue($serializer->has('name'));

        $serializer->remove('name');
        $this->assertFalse($serializer->has('name'));
    }

    public function testHasProperty()
    {
        $serializer = new Serializer(self::DOCUMENT_CLASS);

        $this->assertFalse($serializer->has('name'));

        $serializer->add('name', $this->getSerializerMock());
        $this->assertTrue($serializer->has('name'));
    }

    public function testNodeType()
    {
        $serializer = new Serializer(self::DOCUMENT_CLASS);

        $this->assertEquals('object', $serializer->getNodeType());
    }
}
