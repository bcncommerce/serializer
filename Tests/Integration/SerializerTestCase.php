<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Encoder\CodecInterface;
use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentArrayType;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentNestedType;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentType;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;
use Bcn\Component\Serializer\Type\TypeFactory;

abstract class SerializerTestCase extends TestCase
{
    /**
     * Serialize Document
     *
     * @dataProvider provideDocumentSerializeTestCases
     */
    public function testSerialize($document, $type, $fixture)
    {
        $content = file_get_contents($fixture);

        $serializer = $this->getSerializer();
        $data = $serializer->serialize($document, $type);

        $this->assertEquals($content, $data);
    }

    /**
     * Unserialize Document
     *
     * @dataProvider provideDocumentSerializeTestCases
     */
    public function testUnserialize($document, $type, $fixture)
    {
        $content = file_get_contents($fixture);

        $serializer = $this->getSerializer();
        $unserialized = $serializer->unserialize($content, $type);

        $this->assertEquals($document, $unserialized);
    }

    /**
     * Unserialize Document
     *
     * @dataProvider provideDocumentSerializeTestCases
     */
    public function testUnserializeToObject($document, $type, $fixture)
    {
        $content = file_get_contents($fixture);

        $unserialized = $type != 'document_array' ? new Document() : array();

        $serializer = $this->getSerializer();
        $serializer->unserialize($content, $type, array(), $unserialized);

        $this->assertEquals($document, $unserialized);
    }

    /**
     * @return Serializer
     */
    protected function getSerializer()
    {
        return new Serializer($this->getFactory(), $this->getEncoder());
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
        $factory->addType(new DocumentArrayType());

        return $factory;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideDocumentSerializeTestCases()
    {
        $cases = array();

        $objects = array(
            'document'        => $this->getDocument('flat'),
            'document_nested' => $this->getNestedDocument(),
            'document_array'  => $this->getDocuments(),
        );

        $types = $this->getSupportedTypes();
        foreach ($types as $type) {
            if (!isset($objects[$type])) {
                throw new \Exception(sprintf('Object for type "%s" not defined', $type));
            }

            $cases[$type] = array(
                $objects[$type],
                $type,
                $this->getFixturePath('files/'.$type.'.'.$this->getFileExtension()),
            );
        }

        return $cases;
    }

    /**
     * @return CodecInterface
     */
    abstract protected function getEncoder();

    /**
     * @return string
     */
    abstract protected function getFileExtension();
    /**
     * @return array
     */
    abstract protected function getSupportedTypes();
}
