<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Serializer\ArraySerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class ArraySerializerTest extends TestCase
{
    public function testSerialize()
    {
        $encoder = $this->getEncoderMock();
        $encoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('item'), $this->equalTo('text'));
        $encoder->expects($this->at(1))
            ->method('write')
            ->with('one');
        $encoder->expects($this->at(2))
            ->method('end')
            ->withAnyParameters();
        $encoder->expects($this->at(3))
            ->method('node')
            ->with($this->equalTo('item'), $this->equalTo('text'));
        $encoder->expects($this->at(4))
            ->method('write')
            ->with('two');
        $encoder->expects($this->at(5))
            ->method('end')
            ->withAnyParameters();

        $itemSerializer = $this->getSerializerMock();
        $itemSerializer->expects($this->any())
            ->method('getNodeType')
            ->will($this->returnValue('text'));
        $itemSerializer->expects($this->any())
            ->method('serialize')
            ->with($this->anything(), $this->equalTo($encoder))
            ->will($this->returnCallback(function ($value) use ($encoder) {
                $encoder->write($value);
            }));

        $serializer = new ArraySerializer($itemSerializer, 'item');
        $serializer->serialize(array('one', 'two'), $encoder);
    }

    public function testUnserialize()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('next')
            ->will($this->returnValue(true));
        $decoder->expects($this->at(1))
            ->method('node')
            ->with($this->equalTo('item'), $this->equalTo('text'));
        $decoder->expects($this->at(2))
            ->method('read')
            ->will($this->returnValue('one'));
        $decoder->expects($this->at(3))
            ->method('end');
        $decoder->expects($this->at(4))
            ->method('next')
            ->will($this->returnValue(true));
        $decoder->expects($this->at(5))
            ->method('node')
            ->with($this->equalTo('item'), $this->equalTo('text'));
        $decoder->expects($this->at(6))
            ->method('read')
            ->will($this->returnValue('two'));
        $decoder->expects($this->at(7))
            ->method('end');
        $decoder->expects($this->at(8))
            ->method('next')
            ->will($this->returnValue(false));

        $itemSerializer = $this->getSerializerMock();
        $itemSerializer->expects($this->any())
            ->method('getNodeType')
            ->will($this->returnValue('text'));
        $itemSerializer->expects($this->any())
            ->method('unserialize')
            ->with($this->equalTo($decoder))
            ->will($this->returnCallback(function (DecoderInterface $decoder) {
                return $decoder->read();
            }));

        $serializer = new ArraySerializer($itemSerializer, 'item');
        $data = $serializer->unserialize($decoder);

        $this->assertEquals(array('one', 'two'), $data);
    }

    public function testNodeType()
    {
        $serializer = new ArraySerializer($this->getSerializerMock());

        $this->assertEquals('array', $serializer->getNodeType());
    }
}
