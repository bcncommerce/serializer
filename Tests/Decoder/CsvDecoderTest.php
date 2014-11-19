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
        $decoder->node('table', 'array');
        while ($decoder->node('line', 'object')) {
            if ($decoder->node('description', 'scalar')) {
                $data[] = $decoder->read();
                $decoder->end();
            }

            $decoder->end()->next();
        }
        $decoder->end();

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testDecodeTableWithoutHeaders()
    {
        $stream = $this->getFixtureStream('resources/documents.csv', 'r');
        fgets($stream, 1000); // skip headers line

        $data  = array();

        $decoder = new CsvDecoder($stream, array('name', 'x-description'));
        $decoder->node('table', 'array');
        while ($decoder->node('line', 'object')) {
            if ($decoder->node('x-description', 'scalar')) {
                $data[] = $decoder->read();
                $decoder->end();
            }

            $decoder->end()->next();
        }
        $decoder->end();

        $this->assertEquals($this->getDescriptionValues(), $data);
    }

    public function testTableNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'object');
    }

    public function testTableNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'scalar');
    }

    public function testLineNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'array');
    }

    public function testLineNodeScalarException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'scalar');
    }

    public function testCellNodeArrayException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'array');
    }

    public function testCellNodeObjectException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'object');
    }

    public function testEnterCellNodeException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document',  'object');
        $decoder->node('name',      'scalar');
        $decoder->node('nested',    'scalar');
    }

    public function testReadWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->read();
    }

    public function testNextWrongContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'object');
        $decoder->next();
    }

    public function testReadNonExistentCellException()
    {
        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->node('documents', 'array');
        $decoder->node('document', 'object');

        $this->assertFalse($decoder->node('description', 'scalar'));
    }

    public function testEndOutOfContextException()
    {
        $this->setExpectedException("Exception");

        $stream = $this->getDataStream("name\nName");
        $decoder = new CsvDecoder($stream);
        $decoder->end();
    }

    public function testEmptyTable()
    {
        $stream = $this->getDataStream("name");
        $decoder = new CsvDecoder($stream);
        $decoder->node('table', 'array');

        $this->assertFalse($decoder->isEmpty());
    }

    public function testEmptyCell()
    {
        $stream = $this->getDataStream("name;index\n;5");
        $decoder = new CsvDecoder($stream);
        $decoder->node('table', 'array');
        $decoder->node('line',  'object');
        $decoder->node('name',  'scalar');

        $this->assertTrue($decoder->isEmpty());
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
