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
        $documents = $this->getDocuments();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($documents as $key => $item) {
            $itemNormalizer->expects($this->at($index++))
                ->method('normalize')
                ->with($this->equalTo($item))
                ->will($this->returnValue("Normalized $key"));
        }

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $data = $normalizer->normalize($documents);

        $this->assertEquals(array('Normalized 0', 'Normalized 1'), $data);
    }

    public function testArrayDenormalize()
    {
        $items = $this->getDocumentsData();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemNormalizer->expects($this->at($index++))
                ->method('denormalize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue("Denormalized $key"));
        }

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $data = $normalizer->denormalize($items);

        $this->assertEquals(array('Denormalized 0', 'Denormalized 1'), $data);
    }

    public function testArrayDenormalizeToObject()
    {
        $items = $this->getDocumentsData();
        $itemNormalizer = $this->getNormalizerMock();

        $index = 0;
        foreach ($items as $key => $entry) {
            $itemNormalizer->expects($this->at($index++))
                ->method('denormalize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue("Denormalized $key"));
        }

        $data = new \ArrayObject();

        $normalizer = new ArrayNormalizer($itemNormalizer);
        $normalizer->denormalize($items, $data);

        $this->assertEquals(array('Denormalized 0', 'Denormalized 1'), $data->getArrayCopy());
    }
}
