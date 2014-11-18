<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Serializer\ScalarSerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class ScalarSerializerTest extends TestCase
{
    public function testSerialize()
    {
        $encoder = $this->getEncoderMock();
        $encoder->expects($this->once())
            ->method('write')
            ->with('foo');

        $serializer = new ScalarSerializer();
        $serializer->serialize('foo', $encoder);
    }

    public function testSerializeWithNormalizer()
    {
        $encoder = $this->getEncoderMock();
        $encoder->expects($this->once())
            ->method('write')
            ->with('foo-normalized');

        $serializer = new ScalarSerializer();
        $serializer->setNormalizer(function ($value) {
            return "$value-normalized";
        });

        $serializer->serialize('foo', $encoder);
    }

    public function testUnserialize()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue('foo'));

        $serializer = new ScalarSerializer();
        $data = $serializer->unserialize($decoder);

        $this->assertEquals('foo', $data);
    }

    public function testUnserializeWithDenormalizer()
    {
        $decoder = $this->getDecoderMock();
        $decoder->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue('foo'));

        $serializer = new ScalarSerializer();
        $serializer->setDenormalizer(function ($value) {
            return "$value-denormalized";
        });
        $data = $serializer->unserialize($decoder);

        $this->assertEquals('foo-denormalized', $data);
    }

    public function testNodeType()
    {
        $serializer = new ScalarSerializer();

        $this->assertEquals('scalar', $serializer->getNodeType());
    }

    public function testSetNormalizerException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $serializer = new ScalarSerializer();
        $serializer->setNormalizer(false);
    }

    public function testSetDenormalizerException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $serializer = new ScalarSerializer();
        $serializer->setDenormalizer(false);
    }
}
