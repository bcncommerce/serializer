<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\IteratorNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class IteratorNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $documents = $this->getDocuments();
        $itemNormalizer = $this->getNormalizerMock();

        foreach ($documents as $key => $item) {
            $itemNormalizer->expects($this->at($key))
                ->method('normalize')
                ->with($this->equalTo($item))
                ->will($this->returnValue("Normalized $key"));
        }

        $normalizer = new IteratorNormalizer($itemNormalizer);
        $iterator = $normalizer->normalize($documents);

        $this->assertInstanceOf('Iterator', $iterator);
        $this->assertEquals(array('Normalized 0', 'Normalized 1'), iterator_to_array($iterator));
    }

    public function testNotNormalizeIfNotIterated()
    {
        $itemNormalizer = $this->getNormalizerMock();
        $itemNormalizer->expects($this->once())
            ->method('normalize');

        $normalizer = new IteratorNormalizer($itemNormalizer);
        $iterator = $normalizer->normalize($this->getDocuments());

        $iterator->rewind();
        $iterator->current();
    }

    public function testDenormalize()
    {
        $items = $this->getDocumentsData();
        $itemNormalizer = $this->getNormalizerMock();

        foreach ($items as $key => $entry) {
            $itemNormalizer->expects($this->at($key))
                ->method('denormalize')
                ->with($this->equalTo($entry))
                ->will($this->returnValue("Denormalized $key"));
        }

        $normalizer = new IteratorNormalizer($itemNormalizer);
        $data = $normalizer->denormalize($items);

        $this->assertInstanceOf('Iterator', $data);
        $this->assertEquals(array('Denormalized 0', 'Denormalized 1'), iterator_to_array($data));
    }

    public function testDoNotDenormalizeBeforeIterating()
    {
        $itemNormalizer = $this->getNormalizerMock();
        $itemNormalizer->expects($this->once())
            ->method('denormalize');

        $normalizer = new IteratorNormalizer($itemNormalizer);
        $iterator = $normalizer->denormalize($this->getDocumentsData());

        $iterator->rewind();
        $iterator->current();
    }
}
