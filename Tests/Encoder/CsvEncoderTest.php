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
        foreach ($documents as $document) {
            $encoder->node('document', 'object');
            foreach ($document as $prop => $value) {
                $encoder->node($prop, 'scalar')->write($value)->end();
            }
            $encoder->end();
        }

        $this->assertEquals($this->getFixtureContent('resources/documents.csv'), $encoder->dump());
    }

    public function testLineNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('document', 'array');
    }

    public function testLineNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('document', 'scalar');
    }

    public function testCellNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('document',  'object');
        $decoder->node('name',      'array');
    }

    public function testCellNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
        $decoder->node('document',  'object');
        $decoder->node('name',      'object');
    }

    public function testEnterCellNodeException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('php://temp', 'w');
        $decoder = new CsvEncoder($stream);
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
