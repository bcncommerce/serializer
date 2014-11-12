<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Normalizer;
use Bcn\Component\Serializer\Normalizer\ScalarNormalizer;

class NormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $document = $this->getDocumentObject();

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());
        $data = $normalizer->normalize($document);

        $this->assertEquals($this->getDocumentData(), $data);
    }

    public function testNullNormalize()
    {
        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());
        $data = $normalizer->normalize(null);

        $this->assertNull($data);
    }

    public function testNormalizeNonObjectException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());
        $normalizer->normalize(array());
    }

    public function testNormalizeNested()
    {
        $document = $this->getNestedDocumentObject();

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());
        $normalizer->add('parent', $normalizer);

        $data = $normalizer->normalize($document);

        $this->assertEquals($this->getNestedDocumentData(), $data);
    }

    public function testDenormalize()
    {
        $data = $this->getDocumentData();

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());

        $document = $normalizer->denormalize($data);

        $this->assertEquals($this->getDocumentObject(), $document);
    }

    public function testDenormalizeNested()
    {
        $data = $this->getNestedDocumentData();

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());
        $normalizer->add('parent', $normalizer);

        $document = $normalizer->denormalize($data);

        $this->assertEquals($this->getNestedDocumentObject(), $document);
    }

    public function testDenormalizeToObject()
    {
        $data = $this->getDocumentData();

        $normalizer = new Normalizer(self::DOCUMENT_CLASS);
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());

        $document = new Document();
        $normalizer->denormalize($data, $document);

        $this->assertEquals($this->getDocumentObject(), $document);
    }

    public function testDenormalizeToFactory()
    {
        $data = $this->getDocumentData();

        $normalizer = new Normalizer(function () { return new Document(); });
        $normalizer->add('name', new ScalarNormalizer());
        $normalizer->add('description', new ScalarNormalizer());

        $document = $normalizer->denormalize($data);

        $this->assertEquals($this->getDocumentObject(), $document);
    }
}
