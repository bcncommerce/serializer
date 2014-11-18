<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Decoder;

use Bcn\Component\Serializer\Decoder\ArrayDecoder;
use Bcn\Component\Serializer\Tests\TestCase;

class ArrayDecoderTest extends TestCase
{
    public function testDecodeDocument()
    {
        $decoder = new ArrayDecoder($this->getDocumentData());

        $name = $decoder->node('name', 'scalar')->read();
        $decoder->end();

        $description
              = $decoder->node('description', 'scalar')->read();
        $decoder->end();

        $rank = $decoder->node('rank', 'scalar')->read();
        $decoder->end();

        $rating
              = $decoder->node('rating', 'scalar')->read();
        $decoder->end();

        $this->assertEquals('Test name ',        $name);
        $this->assertEquals('Test description ', $description);
        $this->assertEquals(11,                  $rank);
        $this->assertEquals(93.31,               $rating);
    }

    public function testDecodeStringArray()
    {
        $data = array();
        $decoder = new ArrayDecoder(array('one', 'two'));

        while ($decoder->valid()) {
            $data[] = $decoder->node(null, 'scalar')->read();
            $decoder->end();
            $decoder->next();
        }

        $this->assertEquals(array('one', 'two'), $data);
    }

    public function testDecodeDocumentsArray()
    {
        $names = array();
        $decoder = new ArrayDecoder(array('documents' => $this->getDocumentsData()));
        $decoder->node('documents', 'array');

        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $names[] = $decoder->node('name', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }

        $decoder->end();

        $this->assertEquals(array('Test name one', 'Test name two'), $names);
    }

    public function testDecodeNonExistentProperty()
    {
        $decoder = new ArrayDecoder(array('name' => 'foo'));
        $decoder->node('document', 'object');
        $description = $decoder->node('description', 'scalar')->read();
        $decoder->end();
        $decoder->end();

        $this->assertNull($description);
    }

    public function testDecodeEmptyArray()
    {
        $names = array();

        $decoder = new ArrayDecoder(array('documents' => array()));
        $decoder->node('documents', 'array');

        while ($decoder->valid()) {
            $decoder->node('document', 'object');
            $names[] = $decoder->node('name', 'scalar')->read();
            $decoder->end();
            $decoder->end();
            $decoder->next();
        }

        $decoder->end();

        $this->assertEquals($names, array());
    }
}
