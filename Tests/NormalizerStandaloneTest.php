<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Normalizer;

class NormalizerStandaloneTest extends TestCase
{
    public function testNormalize()
    {
        $document = $this->getDocumentObject();

        $nameNormalizer = $this->getNormalizerMock();
        $nameNormalizer->expects($this->once())
            ->method('normalize')
            ->with($this->equalTo('denormalized'))
            ->will($this->returnValue('normalized'));

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('getValue')
            ->with($this->equalTo($document), $this->equalTo('name'))
            ->will($this->returnValue('denormalized'));

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->setAccessor($accessor);
        $normalizer->add('name', $nameNormalizer);

        $normalized = $normalizer->normalize($document);

        $this->assertEquals(array('name' => 'normalized'), $normalized);
    }

    public function testDenormalize()
    {
        $document = $this->getDocumentObject();

        $factory = function () use ($document) {
            return $document;
        };

        $nameNormalizer = $this->getNormalizerMock();
        $nameNormalizer->expects($this->once())
            ->method('denormalize')
            ->with($this->equalTo('normalized'))
            ->will($this->returnValue('denormalized'));

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('setValue')
            ->with($this->equalTo($document), $this->equalTo('name'), $this->equalTo('denormalized'));

        $normalizer = new Normalizer($factory);
        $normalizer->setAccessor($accessor);
        $normalizer->add('name', $nameNormalizer);

        $normalizer->denormalize(array('name' => 'normalized'));
    }

    public function testDenormalizeToObject()
    {
        $document = $this->getDocumentObject();

        $nameNormalizer = $this->getNormalizerMock();
        $nameNormalizer->expects($this->once())
            ->method('denormalize')
            ->with($this->equalTo('normalized'))
            ->will($this->returnValue('denormalized'));

        $accessor = $this->getAccessorMock();
        $accessor->expects($this->once())
            ->method('setValue')
            ->with($this->equalTo($document), $this->equalTo('name'), $this->equalTo('denormalized'));

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->setAccessor($accessor);
        $normalizer->add('name', $nameNormalizer);

        $normalizer->denormalize(array('name' => 'normalized'), $document);
    }
}
