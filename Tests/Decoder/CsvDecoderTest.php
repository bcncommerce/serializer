<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Decoder;

use Bcn\Component\Serializer\Decoder\CsvDecoder;
use Bcn\Component\Serializer\Tests\TestCase;

class CsvDecoderTest extends TestCase
{
    public function testDecodeTableWithHeaders()
    {
        $stream = fopen($this->getFixturePath('resources/documents.csv'), 'r');
        $data  = array();

        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $data[] = $decoder->node('description', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }
        $decoder->end();

        fclose($stream);

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testDecodeTableWithoutHeaders()
    {
        $stream = fopen($this->getFixturePath('resources/documents.csv'), 'r');
        fgets($stream, 1000); // skip headers line

        $data  = array();

        $decoder = new CsvDecoder($stream, array('name', 'x-description'));
        $decoder->node('documents', 'array');
        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $data[] = $decoder->node('x-description', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }
        $decoder->end();

        fclose($stream);

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testRootNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode(""), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'object');
    }

    public function testRootNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode(""), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'scalar');
    }

    public function testLineNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode(""), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'array');
    }

    public function testLineNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'scalar');
    }

    public function testCellNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'array');
    }

    public function testCellNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'object');
    }

    public function testEnterCellNodeException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'scalar');
        $decoder->node('nested',    'scalar');
    }

    public function testReadWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->read();
    }

    public function testNextWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->next();
    }

    public function testEndOutOfContextException()
    {
        $this->setExpectedException("Exception");

        $stream = fopen('data://text/plain;base64,'.base64_encode("name"), 'r');
        $decoder = new CsvDecoder($stream);
        $decoder->end();
    }

    /**
     * @return array
     */
    protected function getDescriptionValues()
    {
        return array(
            'World',
            'This text ";" have quotes',
            'Has one extra column',
            'Miss one column',
        );
    }
}
