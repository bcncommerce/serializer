<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Decoder;

use Bcn\Component\Serializer\Decoder\JsonDecoder;
use Bcn\Component\Serializer\Tests\TestCase;

class JsonDecoderTest extends TestCase
{
    public function testDecodeDocument()
    {
        $data = array('name' => null, 'description' => null, 'rank' => null, 'rating' => null);

        $decoder = new JsonDecoder(json_encode($this->getDocumentData()));
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

    public function testDecodeStringArray()
    {
        $data = array();
        $decoder = new JsonDecoder('["one", "two"]');
        $decoder->node('strings', 'array');
        while ($decoder->node('item', 'scalar')) {
            $data[] = $decoder->read();
            $decoder->end()->next();
        }
        $decoder->end();

        $this->assertEquals(array('one', 'two'), $data);
    }

    public function testDecodeDocumentsArray()
    {
        $names = array();
        $decoder = new JsonDecoder(json_encode($this->getDocumentsData()));
        $decoder->node('documents', 'array');

        while ($decoder->node('document', 'object')) {
            if ($decoder->node('name', 'scalar')) {
                $names[] = $decoder->read();
                $decoder->end();
            }
            $decoder->end()->next();
        }

        $decoder->end();

        $this->assertEquals(array('Test name one', 'Test name two'), $names);
    }

    public function testDecodeNonExistentProperty()
    {
        $decoder = new JsonDecoder('{"name": "Test"}');
        $decoder->node('document', 'object');

        $this->assertFalse($decoder->node('description', 'scalar'));
    }

    public function testDecodeEmptyArray()
    {
        $names = array();

        $decoder = new JsonDecoder('[]');
        $decoder->node('documents', 'array');

        while ($decoder->node('document', 'object')) {
            if ($decoder->node('name', 'scalar')) {
                $names[] = $decoder->read();
                $decoder->end();
            }
            $decoder->end()->next();
        }

        $decoder->end();

        $this->assertEquals($names, array());
    }
}
