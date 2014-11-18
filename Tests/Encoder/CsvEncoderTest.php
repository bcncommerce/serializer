<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder;

use Bcn\Component\Serializer\Encoder\CsvEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class CsvEncoderTest extends TestCase
{
    public function testDocumentArrayEncode()
    {
        $stream = fopen("php://temp", "rw+");
        $documents = $this->getDocumentsData();

        $encoder = new CsvEncoder($stream);
        $encoder->node('documents', 'array');
        foreach ($documents as $document) {
            $encoder->node('document', 'object');
            foreach ($document as $prop => $value) {
                $encoder->node($prop, 'scalar')->write($value)->end();
            }
            $encoder->end();
        }
        $encoder->end();
        rewind($stream);

        $this->assertEquals($this->getFixtureContent('resources/documents.csv'), stream_get_contents($stream));
    }

    public function testRootNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'object');
    }

    public function testRootNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'scalar');
    }

    public function testLineNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'array');
    }

    public function testLineNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'scalar');
    }

    public function testCellNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'array');
    }

    public function testCellNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'object');
    }

    public function testEnterCellNodeException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'scalar');
        $decoder->node('nested',    'scalar');
    }

    public function testWriteWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->write("foo");
    }

    public function testEndOutOfContextException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->end();
    }
}
