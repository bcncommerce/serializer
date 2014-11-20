<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Encoder\XmlEncoder;
use Bcn\Component\Serializer\SerializerFactory;
use Bcn\Component\Serializer\Encoder\ArrayEncoder;
use Bcn\Component\Serializer\Encoder\CsvEncoder;
use Bcn\Component\Serializer\Encoder\EncoderInterface;
use Bcn\Component\Serializer\Encoder\JsonEncoder;
use Bcn\Component\Serializer\Tests\Integration\Type\Extension\DocumentTypeExtension;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;

/**
 * @group integration
 */
class DocumentSerializeTest extends TestCase
{
    /**
     * @param EncoderInterface $encoder
     * @param $expected
     * @dataProvider provideDocumentEncoders
     */
    public function testDocumentSerialize(EncoderInterface $encoder, $expected)
    {
        $document = $this->getDocument('flat');

        $this->getFactory()->serialize($document, $encoder, 'document_root');

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
            'xml'  => array(
                new XmlEncoder($this->getDataStream()),
                $this->getFixtureContent('resources/document.xml'),
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

        $this->getFactory()->serialize($document, $encoder, 'document_nested');

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
            'xml'  => array(
                new XmlEncoder($this->getDataStream()),
                $this->getFixtureContent('resources/document_nested.xml'),
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
        $documents = $this->getDocuments();

        $this->getFactory()->serialize($documents, $encoder, 'document_array');

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
            'xml'  => array(
                new XmlEncoder($this->getDataStream()),
                $this->getFixtureContent('resources/document_array.xml'),
            ),
        );
    }

    /**
     * @return SerializerFactory
     */
    protected function getFactory()
    {
        $factory = new SerializerFactory();
        $factory->extend(new CoreTypesExtension());
        $factory->extend(new DocumentTypeExtension());

        return $factory;
    }
}
