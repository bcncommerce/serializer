<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Serializer\RootSerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class RootSerializerTest extends TestCase
{
    public function testSerialize()
    {
        $encoder = $this->getEncoderMock();
        $encoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('root'), $this->equalTo('text'));
        $encoder->expects($this->at(1))
            ->method('write')
            ->with('one');
        $encoder->expects($this->at(2))
            ->method('end')
            ->withAnyParameters();

        $innerSerializer = $this->getSerializerMock();
        $innerSerializer->expects($this->any())
            ->method('getNodeType')
            ->will($this->returnValue('text'));
        $innerSerializer->expects($this->any())
            ->method('serialize')
            ->with($this->anything(), $this->equalTo($encoder))
            ->will($this->returnCallback(function ($value) use ($encoder) {
                $encoder->write($value);
            }));

        $serializer = new RootSerializer('root', $innerSerializer);
        $serializer->serialize('one', $encoder);
    }

    public function testUnserialize()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('root'), $this->equalTo('text'))
            ->will($this->returnValue(true));
        $decoder->expects($this->at(1))
            ->method('read')
            ->will($this->returnValue('one'));
        $decoder->expects($this->at(2))
            ->method('end');

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

        $serializer = new RootSerializer('root', $itemSerializer);
        $data = $serializer->unserialize($decoder);

        $this->assertEquals('one', $data);
    }

    public function testUnserializeEmptyDocument()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('node')
            ->with($this->equalTo('root'), $this->anything())
            ->will($this->returnValue(false));

        $serializer = new RootSerializer('root', $this->getSerializerMock());
        $data = $serializer->unserialize($decoder);

        $this->assertNull($data);
    }

    public function testNodeType()
    {
        $serializer = new RootSerializer('root', $this->getSerializerMock());

        $this->assertEquals('object', $serializer->getNodeType());
    }
}
