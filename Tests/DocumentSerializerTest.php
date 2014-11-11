<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Type\TextType;
use Bcn\Component\Serializer\Type\TypeFactory;
use Bcn\Component\Serializer\Tests\Type\DocumentType;

class DocumentSerializerTest extends TestCase
{
    /**
     * Serialize Document
     */
    public function testDocumentSerialize()
    {
        $document = $this->getNestedDocumentObject();

        $serializer = $this->getFactory()
            ->create('document');

        $data = $serializer->serialize($document);

        $this->assertEquals($this->getNestedDocumentData(), $data);
    }

    /**
     * Unserialize Document
     */
    public function testDocumentUnserialize()
    {
        $data = $this->getNestedDocumentData();

        $serializer = $this->getFactory()
            ->create('document');

        $document = $serializer->unserialize($data);

        $this->assertEquals($this->getNestedDocumentObject(), $document);
    }

    /**
     * @return TypeFactory
     * @throws \Exception
     */
    protected function getFactory()
    {
        $factory = new TypeFactory();
        $factory->addType(new TextType());
        $factory->addType(new DocumentType());

        return $factory;
    }
}
