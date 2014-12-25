<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\TypeSerializer;

class TypeSerializerTest extends TestCase
{
    public function testSerializeDefaultFormat()
    {
        $object = new \stdClass();
        $stream = $this->getDataStream();

        $serializer = $this->getSerializerMock();
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($object, 'foo', $stream, 'bar', array());

        $typeSerializer = new TypeSerializer($serializer, 'foo', array(), 'bar');
        $typeSerializer->serialize($object, $stream);
    }

    public function testSerializeCustomFormat()
    {
        $object = new \stdClass();
        $stream = $this->getDataStream();

        $serializer = $this->getSerializerMock();
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($object, 'foo', $stream, 'baz', array());

        $typeSerializer = new TypeSerializer($serializer, 'foo', array(), 'bar');
        $typeSerializer->serialize($object, $stream, 'baz');
    }

    public function testUnserializeDefaultFormat()
    {
        $object = new \stdClass();
        $stream = $this->getDataStream();

        $serializer = $this->getSerializerMock();
        $serializer->expects($this->once())
            ->method('unserialize')
            ->with($stream, 'bar', 'foo', array(), $object);

        $typeSerializer = new TypeSerializer($serializer, 'foo', array(), 'bar');
        $typeSerializer->unserialize($stream, $object);
    }

    public function testUnserializeCustomFormat()
    {
        $object = new \stdClass();
        $stream = $this->getDataStream();

        $serializer = $this->getSerializerMock();
        $serializer->expects($this->once())
            ->method('unserialize')
            ->with($stream, 'baz', 'foo', array(), $object);

        $typeSerializer = new TypeSerializer($serializer, 'foo', array(), 'bar');
        $typeSerializer->unserialize($stream, $object, 'baz');
    }
}
