<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Decoder;

use Bcn\Component\Serializer\Decoder\XmlDecoder;
use Bcn\Component\Serializer\Tests\TestCase;

class XmlDecoderTest extends TestCase
{
    public function testNodeEnterValid()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));

        $this->assertTrue($decoder->node('document'));
    }

    public function testNodeEnterInvalid()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));

        $this->assertFalse($decoder->node('invalid'));
    }

    public function testNodeEnterInnerFirst()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));

        $decoder->node('document');
        $this->assertTrue($decoder->node('name'));
    }

    public function testNodeEnterInnerFirstWithSecondTry()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));

        $decoder->node('document');
        $this->assertFalse($decoder->node('prename'));
        $this->assertTrue($decoder->node('name'));
    }

    public function testNodeEnterInnerSecond()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));

        $decoder->node('document');
        $this->assertFalse($decoder->node('description'));
    }

    public function testNodeEnterEmptyElement()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/empty_elements.xml'));

        $decoder->node('collection', 'array');
        $decoder->node('item', 'object');

        $this->assertFalse($decoder->node('inner'));
        $decoder->end();

        $this->assertTrue($decoder->node('item'));
    }

    public function testEndSkipInnerNodes()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/nested.xml'));

        $decoder->node('document');
        $decoder->node('status');

        $decoder->end();

        $this->assertTrue($decoder->node('definition'));
    }

    public function testNextIterating()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/iterations.xml'));

        $iteration = 0;
        if ($decoder->node('collection')) {
            while ($decoder->node('item', 'scalar')) {
                $iteration++;
                $decoder->end()->next();
            }

            $decoder->end();
        }

        $this->assertEquals(5, $iteration);
    }

    public function testRead()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));
        $decoder->node('document', 'object');
        $decoder->node('name', 'scalar');

        $this->assertEquals('Test name ', $decoder->read());
    }

    public function testReadEmptyElement()
    {
        $decoder = new XmlDecoder($this->getFixtureUri('resources/empty_elements.xml'));
        $decoder->node('collection', 'array');
        $decoder->node('item', 'scalar');

        $this->assertNull($decoder->read());
    }

    public function testUnexpectedEnding()
    {
        $this->setExpectedException("Exception");

        $decoder = new XmlDecoder($this->getFixtureUri('resources/unexpected_end.xml'));
        $decoder->node('collection', 'array');

        while ($decoder->node('item', 'scalar')) {
            $decoder->end()->next();
        }
    }

    public function testDecodeDocument()
    {
        $data = array('name' => null, 'description' => null, 'rank' => null, 'rating' => null);

        $decoder = new XmlDecoder($this->getFixtureUri('resources/document.xml'));
        if ($decoder->node("document", "object")) {
            if ($decoder->node('name', 'scalar')) {
                $data['name'] = $decoder->read();
                $decoder->end();
            }

            if ($decoder->node('description', 'scalar')) {
                $data['description'] = $decoder->read();
                $decoder->end();
            }

            if ($decoder->node('rank', 'scalar')) {
                $data['rank'] = $decoder->read();
                $decoder->end();
            }

            if ($decoder->node('rating', 'scalar')) {
                $data['rating'] = $decoder->read();
                $decoder->end();
            }
            $decoder->end();
        }

        $this->assertEquals($this->getDocumentData(), $data);
    }
}
