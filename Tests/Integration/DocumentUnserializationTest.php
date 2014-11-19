<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration;

use Bcn\Component\Serializer\Decoder\ArrayDecoder;
use Bcn\Component\Serializer\Decoder\CsvDecoder;
use Bcn\Component\Serializer\Decoder\DecoderInterface;
use Bcn\Component\Serializer\Decoder\JsonDecoder;
use Bcn\Component\Serializer\Decoder\XmlDecoder;
use Bcn\Component\Serializer\SerializerFactory;
use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;
use Bcn\Component\Serializer\Tests\Integration\Type\Extension\DocumentTypeExtension;

/**
 * @group integration
 */
class DocumentUnserializationTest extends TestCase
{
    /**
     * @param DecoderInterface $decoder
     * @dataProvider provideDocumentDecoders
     */
    public function testDocumentUnserialize(DecoderInterface $decoder)
    {
        $document = $this->getFactory()->unserialize($decoder, 'document_root');

        $this->assertEquals($this->getDocument('flat'), $document);
    }

    public function provideDocumentDecoders()
    {
        return array(
            'array' => array(
                new ArrayDecoder($this->getDocumentData('flat')),
            ),
            'json'  => array(
                new JsonDecoder($this->getFixtureContent('resources/document.json')),
            ),
            'xml'  => array(
                new XmlDecoder($this->getFixtureUri('resources/document.xml')),
            ),
        );
    }

    /**
     * @param DecoderInterface $decoder
     * @dataProvider provideNestedDocumentDecoders
     */
    public function testNestedDocumentUnserialize(DecoderInterface $decoder)
    {
        $document = $this->getFactory()->unserialize($decoder, 'document_nested');

        $this->assertEquals($this->getNestedDocument(), $document);
    }

    public function provideNestedDocumentDecoders()
    {
        return array(
            'array' => array(
                new ArrayDecoder($this->getNestedDocumentData()),
            ),
            'json'  => array(
                new JsonDecoder($this->getFixtureContent('resources/document_nested.json')),
            ),
            'xml'  => array(
                new XmlDecoder($this->getFixtureUri('resources/document_nested.xml')),
            ),
        );
    }

    /**
     * @param DecoderInterface $decoder
     * @dataProvider provideDocumentArrayDecoders
     */
    public function testDocumentArrayUnserialize(DecoderInterface $decoder)
    {
        $documents = $this->getFactory()->unserialize($decoder, 'document_array');

        $this->assertEquals($this->getDocuments(), $documents);
    }

    public function provideDocumentArrayDecoders()
    {
        return array(
            'array' => array(
                new ArrayDecoder($this->getDocumentsData()),
            ),
            'json'  => array(
                new JsonDecoder($this->getFixtureContent('resources/document_array.json')),
            ),
            'csv'  => array(
                new CsvDecoder($this->getFixtureStream('resources/document_array.csv')),
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
