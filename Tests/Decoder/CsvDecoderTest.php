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
        $stream = $this->getFixtureStream('resources/documents.csv', 'r');
        $data  = array();

        $decoder = new CsvDecoder($stream);
        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $data[] = $decoder->node('description', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testDecodeTableWithoutHeaders()
    {
        $stream = $this->getFixtureStream('resources/documents.csv', 'r');
        fgets($stream, 1000); // skip headers line

        $data  = array();

        $decoder = new CsvDecoder($stream, array('name', 'x-description'));
        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $data[] = $decoder->node('x-description', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testLineNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document', 'array');
    }

    public function testLineNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document', 'scalar');
    }

    public function testCellNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document',  'object');
        $decoder->node('name',      'array');
    }

    public function testCellNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document',  'object');
        $decoder->node('name',      'object');
    }

    public function testEnterCellNodeException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document',  'object');
        $decoder->node('name',      'scalar');
        $decoder->node('nested',    'scalar');
    }

    public function testReadWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->read();
    }

    public function testNextWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document', 'object');
        $decoder->next();
    }

    public function testEndOutOfContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->end();
    }

    public function testExists()
    {
        $stream = $this->getDataStream("name;description\nName;Description");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document', 'object');

        $this->assertTrue($decoder->exists('name'));
    }

    public function testNotExists()
    {
        $stream = $this->getDataStream("name;description\nName;Description");
        $decoder = new CsvDecoder($stream);
        $decoder->node('document', 'object');

        $this->assertFalse($decoder->exists('rank'));
    }

    public function testExistsWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name;description\nName;Description");
        $decoder = new CsvDecoder($stream);
        $decoder->exists('name');
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
