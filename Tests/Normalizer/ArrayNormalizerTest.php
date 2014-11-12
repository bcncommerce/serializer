<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\ArrayNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class ArrayNormalizerTest extends TestCase
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

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $data = $normalizer->normalize($documents);

        $this->assertEquals(array_keys($documents), $data);
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

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $data = $normalizer->denormalize($items);

        $this->assertEquals(array_keys($items), $data);
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

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $normalizer->denormalize($items, $data);

        $this->assertEquals(array_keys($items), $data->getArrayCopy());
    }
}
