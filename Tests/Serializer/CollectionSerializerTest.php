<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Serializer\CollectionSerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class CollectionSerializerTest extends TestCase
{
    public function testArraySerialize()
    {
        $documents = $this->getDocumentObjectCollection();
        $itemSerializer = $this->getSerializerMock();

        $index = 0;
        foreach ($documents as $key => $item) {
            $itemSerializer->expects($this->at($index++))
                ->method('serialize')
                ->with($this->equalTo($item))
                ->will($this->returnValue($key));
        }

        $serializer = new CollectionSerializer($itemSerializer);
        $data = $serializer->serialize($documents);

        $expected = array_combine(array_keys($documents), array_keys($documents));
        $this->assertEquals($expected, $data);
    }

    public function testArrayUnserialize()
    {
        $items = $this->getDocumentDataCollection();
        $itemSerializer = $this->getSerializerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemSerializer->expects($this->at($index++))
                ->method('unserialize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue($key));
        }

        $serializer = new CollectionSerializer($itemSerializer);
        $data = $serializer->unserialize($items);

        $expected = array_combine(array_keys($items), array_keys($items));
        $this->assertEquals($expected, $data);
    }

    public function testArrayUnserializeToObject()
    {
        $items = $this->getDocumentDataCollection();
        $itemSerializer = $this->getSerializerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemSerializer->expects($this->at($index++))
                ->method('unserialize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue($key));
        }

        $data = new \ArrayObject();

        $serializer = new CollectionSerializer($itemSerializer);
        $serializer->unserialize($items, $data);

        $expected = array_combine(array_keys($items), array_keys($items));
        $this->assertEquals($expected, $data->getArrayCopy());
    }
}
