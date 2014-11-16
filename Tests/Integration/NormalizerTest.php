<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Tests\Integration\Type\DocumentNestedType;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentType;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;
use Bcn\Component\Serializer\Type\TypeFactory;

class NormalizerTest extends TestCase
{
    /**
     * Normalize Document
     */
    public function testDocumentNormalize()
    {
        $document = $this->getNestedDocument();

        $normalizer = $this->getFactory()
            ->create('document_nested');

        $data = $normalizer->normalize($document);

        $this->assertEquals($this->getNestedDocumentData(), $data);
    }

    /**
     * Denormalize Document
     */
    public function testDocumentDenormalize()
    {
        $data = $this->getNestedDocumentData();

        $normalizer = $this->getFactory()
            ->create('document_nested');

        $document = $normalizer->denormalize($data);

        $this->assertEquals($this->getNestedDocument(), $document);
    }

    /**
     * @return TypeFactory
     * @throws \Exception
     */
    protected function getFactory()
    {
        $factory = new TypeFactory();
        $factory->extend(new CoreTypesExtension());
        $factory->addType(new DocumentType());
        $factory->addType(new DocumentNestedType());

        return $factory;
    }
}
