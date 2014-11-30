<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Serializer;
use Bcn\Component\Serializer\Type\Extension\CoreExtension;
use Bcn\Component\Serializer\Tests\Type\Extension\TestExtension;

/**
 * @group integration
 */
class SerializerTest extends TestCase
{
    public function testNormalize()
    {
        $data = $this->getSerializer()
            ->normalize($this->getDocument(), 'document');

        $this->assertEquals($this->getDocumentData(), $data);
    }

    public function testDenormalize()
    {
        $document = $this->getSerializer()
            ->denormalize($this->getDocumentData(), 'document');

        $this->assertEquals($this->getDocument(), $document);
    }

    /**
     * @dataProvider getDocumentFormats
     */
    public function testDocumentSerialize($format, $content)
    {
        $document = $this->getDocument('flat');
        $stream   = $this->getDataStream();

        $this->getSerializer()->serialize($document, 'document', $stream, $format);

        $this->assertEquals($content, $this->getStreamContent($stream));
    }

    /**
     * @dataProvider getNestedDocumentFormats
     */
    public function testDocumentWithParentSerialize($format, $content)
    {
        $document = $this->getNestedDocument();
        $stream   = $this->getDataStream();

        $this->getSerializer()->serialize($document, 'document', $stream, $format);

        $this->assertEquals($content, $this->getStreamContent($stream));
    }

    /**
     * @dataProvider getDocumentArrayFormats
     */
    public function testDocumentArraySerialize($format, $content)
    {
        $documents = $this->getDocuments();
        $stream    = $this->getDataStream();

        $this->getSerializer()->serialize($documents, 'document_array', $stream, $format);

        $this->assertEquals($content, $this->getStreamContent($stream));
    }

    /**
     * @dataProvider getAttributesFormats
     */
    public function testAttributesSerialize($format, $content)
    {
        $attributes = $this->getAttributes();
        $stream     = $this->getDataStream();

        $this->getSerializer()->serialize($attributes, 'attributes', $stream, $format);

        $this->assertEquals($content, $this->getStreamContent($stream));
    }

    /**
     * @dataProvider getDocumentFormats
     */
    public function testDocumentUnserialize($format, $content)
    {
        $stream = $this->getDataStream($content);

        $document = $this->getSerializer()
            ->unserialize($stream, $format, 'document');

        $this->assertEquals($this->getDocument('flat'), $document);
    }

    /**
     * @dataProvider getNestedDocumentFormats
     */
    public function testDocumentWithParentUnserialize($format, $content)
    {
        $stream = $this->getDataStream($content);

        $document = $this->getSerializer()
            ->unserialize($stream, $format, 'document');

        $this->assertEquals($this->getNestedDocument(), $document);
    }

    /**
     * @dataProvider getDocumentArrayFormats
     */
    public function testDocumentArrayUnserialize($format, $content)
    {
        $stream    = $this->getDataStream($content);

        $documents = $this->getSerializer()
            ->unserialize($stream, $format, 'document_array');

        $this->assertEquals($this->getDocuments(), $documents);
    }

    /**
     * @dataProvider getAttributesFormats
     */
    public function testAttributesUnserialize($format, $content)
    {
        $stream    = $this->getDataStream($content);

        $documents = $this->getSerializer()
            ->unserialize($stream, $format, 'attributes');

        $this->assertEquals($this->getAttributes(), $documents);
    }

    /**
     * @return Serializer
     */
    protected function getSerializer()
    {
        $serializer = new Serializer();
        $serializer->extend(new CoreExtension());
        $serializer->extend(new TestExtension());

        return $serializer;
    }
}
