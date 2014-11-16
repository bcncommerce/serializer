<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Serializer;

class SerializerTest extends TestCase
{
    public function testSerialize()
    {
        $document    = $this->getDocumentObject();
        $normalized  = $this->getDocumentData();
        $options     = array('foo' => 'moo');

        $normalizer = $this->getNormalizerMock();
        $normalizer->expects($this->once())
            ->method('normalize')
            ->with($this->equalTo($document))
            ->will($this->returnValue($normalized));

        $typeFactory = $this->getTypeFactoryMock();
        $typeFactory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('foo-type'))
            ->will($this->returnValue($normalizer));

        $encoder = $this->getEncoderDecoderMock();
        $encoder->expects($this->once())
            ->method('encode')
            ->with($this->equalTo($normalized))
            ->will($this->returnValue('encoded'));

        $serializer = new Serializer($typeFactory, $encoder);
        $encoded = $serializer->serialize($document, 'foo-type', $options);

        $this->assertEquals('encoded', $encoded);
    }

    public function testUnserialize()
    {
        $normalized  = $this->getDocumentData();
        $options     = array('foo' => 'moo');

        $encoder = $this->getEncoderDecoderMock();
        $encoder->expects($this->once())
            ->method('decode')
            ->with($this->equalTo('encoded'))
            ->will($this->returnValue($normalized));

        $normalizer = $this->getNormalizerMock();
        $normalizer->expects($this->once())
            ->method('denormalize')
            ->with($this->equalTo($normalized))
            ->will($this->returnValue($this->getDocumentObject()));

        $typeFactory = $this->getTypeFactoryMock();
        $typeFactory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('foo-type'))
            ->will($this->returnValue($normalizer));

        $serializer = new Serializer($typeFactory, $encoder);
        $document = $serializer->unserialize('encoded', 'foo-type', $options);

        $this->assertInstanceOf(self::DOCUMENT_CLASS, $document);
    }
}
