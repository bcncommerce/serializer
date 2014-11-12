<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\CollectionNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class CollectionNormalizerTest extends TestCase
{
    public function testArrayNormalize()
    {
        $documents = $this->getDocumentObjectCollection();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($documents as $key => $item) {
            $itemNormalizer->expects($this->at($index++))
                ->method('normalize')
                ->with($this->equalTo($item))
                ->will($this->returnValue($key));
        }

        $normalizer = new CollectionNormalizer($itemNormalizer);
        $data = $normalizer->normalize($documents);

        $expected = array_combine(array_keys($documents), array_keys($documents));
        $this->assertEquals($expected, $data);
    }

    public function testArrayDenormalize()
    {
        $items = $this->getDocumentDataCollection();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemNormalizer->expects($this->at($index++))
                ->method('denormalize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue($key));
        }

        $normalizer = new CollectionNormalizer($itemNormalizer);
        $data = $normalizer->denormalize($items);

        $expected = array_combine(array_keys($items), array_keys($items));
        $this->assertEquals($expected, $data);
    }

    public function testArrayDenormalizeToObject()
    {
        $items = $this->getDocumentDataCollection();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemNormalizer->expects($this->at($index++))
                ->method('denormalize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue($key));
        }

        $data = new \ArrayObject();

        $normalizer = new CollectionNormalizer($itemNormalizer);
        $normalizer->denormalize($items, $data);

        $expected = array_combine(array_keys($items), array_keys($items));
        $this->assertEquals($expected, $data->getArrayCopy());
    }
}
