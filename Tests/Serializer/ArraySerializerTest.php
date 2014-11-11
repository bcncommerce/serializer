<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Serializer\ArraySerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class ArraySerializerTest extends TestCase
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

        $serializer = new ArraySerializer($itemSerializer);
        $data = $serializer->serialize($documents);

        $this->assertEquals(array_keys($documents), $data);
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

        $serializer = new ArraySerializer($itemSerializer);
        $data = $serializer->unserialize($items);

        $this->assertEquals(array_keys($items), $data);
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

        $serializer = new ArraySerializer($itemSerializer);
        $serializer->unserialize($items, $data);

        $this->assertEquals(array_keys($items), $data->getArrayCopy());
    }
}
