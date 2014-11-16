<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Encoder\Json\JsonEncoder;
use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;
use Bcn\Component\Serializer\Type\TypeFactory;
use Bcn\Component\Serializer\Tests\Type\DocumentType;

class DocumentSerializerTest extends TestCase
{
    /**
     * Serialize Document to JSON
     */
    public function testDocumentSerializeToJson()
    {
        $document = $this->getNestedDocumentObject();

        $serializer = $this->getJsonSerializer();
        $data = $serializer->serialize($document, 'document');

        $this->assertJsonStringEqualsJsonString($this->getNestedDocumentEncoded(), $data);
    }

    /**
     * Unserialize Document from JSON
     */
    public function testDocumentUnserializeFromJson()
    {
        $data = $this->getNestedDocumentEncoded();

        $serializer = $this->getJsonSerializer();
        $document = $serializer->unserialize($data, 'document');

        $this->assertEquals($this->getNestedDocumentObject(), $document);
    }

    /**
     * Unserialize Document from JSON
     */
    public function testDocumentUnserializeFromJsonToObject()
    {
        $data = $this->getNestedDocumentEncoded();
        $document = $this->getDocumentObject();

        $serializer = $this->getJsonSerializer();
        $serializer->unserialize($data, 'document', array(), $document);

        $this->assertEquals($this->getNestedDocumentObject(), $document);
    }

    /**
     * @return Serializer
     */
    protected function getJsonSerializer()
    {
        return new Serializer($this->getFactory(), new JsonEncoder());
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

        return $factory;
    }
}
