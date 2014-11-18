<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Encoder\ArrayEncoder;
use Bcn\Component\Serializer\Encoder\CsvEncoder;
use Bcn\Component\Serializer\Encoder\EncoderInterface;
use Bcn\Component\Serializer\Encoder\JsonEncoder;
use Bcn\Component\Serializer\Tests\Integration\Type\Extension\DocumentTypeExtension;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;
use Bcn\Component\Serializer\Type\TypeFactory;

/**
 * @group integration
 */
class DocumentSerializationTest extends TestCase
{
    /**
     * @param EncoderInterface $encoder
     * @param $expected
     * @dataProvider provideDocumentEncoders
     */
    public function testDocumentSerialize(EncoderInterface $encoder, $expected)
    {
        $document = $this->getDocument('flat');

        $this->getFactory()->create('document', array())
            ->serialize($document, $encoder);

        $this->assertEquals($expected, $encoder->dump());
    }

    public function provideDocumentEncoders()
    {
        return array(
            'array' => array(
                new ArrayEncoder(),
                $this->getDocumentData('flat'),
            ),
            'json'  => array(
                new JsonEncoder(),
                $this->getFixtureContent('resources/document.json'),
            ),
        );
    }

    /**
     * @param EncoderInterface $encoder
     * @param $expected
     * @dataProvider provideNestedDocumentEncoders
     */
    public function testNestedDocumentSerialize(EncoderInterface $encoder, $expected)
    {
        $document = $this->getNestedDocument();

        $this->getFactory()->create('document_nested', array())
            ->serialize($document, $encoder);

        $this->assertEquals($expected, $encoder->dump());
    }

    public function provideNestedDocumentEncoders()
    {
        return array(
            'array' => array(
                new ArrayEncoder(),
                $this->getNestedDocumentData(),
            ),
            'json'  => array(
                new JsonEncoder(),
                $this->getFixtureContent('resources/document_nested.json'),
            ),
        );
    }

    /**
     * @param EncoderInterface $encoder
     * @param $expected
     * @dataProvider provideDocumentArrayEncoders
     */
    public function testDocumentArraySerialize(EncoderInterface $encoder, $expected)
    {
        $document = $this->getDocuments();

        $this->getFactory()->create('document_array', array())
            ->serialize($document, $encoder);

        $this->assertEquals($expected, $encoder->dump());
    }

    public function provideDocumentArrayEncoders()
    {
        return array(
            'array' => array(
                new ArrayEncoder(),
                $this->getDocumentsData(),
            ),
            'json' => array(
                new JsonEncoder(),
                json_encode($this->getDocumentsData()),
            ),
            'csv' => array(
                new CsvEncoder(fopen('php://temp', 'rw+')),
                $this->getFixtureContent('resources/document_array.csv'),
            ),
        );
    }

    /**
     * @return TypeFactory
     */
    protected function getFactory()
    {
        $factory = new TypeFactory();
        $factory->extend(new CoreTypesExtension());
        $factory->extend(new DocumentTypeExtension());

        return $factory;
    }
}
