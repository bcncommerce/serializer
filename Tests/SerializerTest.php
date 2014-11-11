<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Serializer\ScalarSerializer;

class SerializerTest extends TestCase
{
    public function testSerialize()
    {
        $document = $this->getDocumentObject();

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());
        $data = $serializer->serialize($document);

        $this->assertEquals($this->getDocumentData(), $data);
    }

    public function testNullSerialize()
    {
        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());
        $data = $serializer->serialize(null);

        $this->assertNull($data);
    }

    public function testSerializeNonObjectException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());
        $serializer->serialize(array());
    }

    public function testSerializeNested()
    {
        $document = $this->getNestedDocumentObject();

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());
        $serializer->add('parent', $serializer);

        $data = $serializer->serialize($document);

        $this->assertEquals($this->getNestedDocumentData(), $data);
    }

    public function testUnserialize()
    {
        $data = $this->getDocumentData();

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());

        $document = $serializer->unserialize($data);

        $this->assertEquals($this->getDocumentObject(), $document);
    }

    public function testUnserializeNested()
    {
        $data = $this->getNestedDocumentData();

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());
        $serializer->add('parent', $serializer);

        $document = $serializer->unserialize($data);

        $this->assertEquals($this->getNestedDocumentObject(), $document);
    }

    public function testUnserializeToObject()
    {
        $data = $this->getDocumentData();

        $serializer = new Serializer(self::DOCUMENT_CLASS);
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());

        $document = new Document();
        $serializer->unserialize($data, $document);

        $this->assertEquals($this->getDocumentObject(), $document);
    }

    public function testUnserializeToFactory()
    {
        $data = $this->getDocumentData();

        $serializer = new Serializer(function () { return new Document(); });
        $serializer->add('name', new ScalarSerializer());
        $serializer->add('description', new ScalarSerializer());

        $document = $serializer->unserialize($data);

        $this->assertEquals($this->getDocumentObject(), $document);
    }
}
