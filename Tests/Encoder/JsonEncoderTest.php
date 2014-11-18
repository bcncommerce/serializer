<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder;

use Bcn\Component\Serializer\Encoder\JsonEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class JsonEncoderTest extends TestCase
{
    public function testEncodeStructure()
    {
        $data = '{"first": "one", "second": "two"}';

        $encoder = new JsonEncoder();
        $encoder->node('first', 'scalar')->write('one')->end();
        $encoder->node('second', 'scalar')->write('two')->end();

        $this->assertJsonStringEqualsJsonString($data, $encoder->dump());
    }

    public function testEncodeArray()
    {
        $data = '["one", "two"]';

        $encoder = new JsonEncoder();
        $encoder->node(null, 'scalar')->write('one')->end();
        $encoder->node(null, 'scalar')->write('two')->end();

        $this->assertJsonStringEqualsJsonString($data, $encoder->dump());
    }

    public function testEncodeScalar()
    {
        $encoder = new JsonEncoder();
        $encoder->write('foo');

        $this->assertJsonStringEqualsJsonString('"foo"', $encoder->dump());
    }

    public function testEncodeDocumentArray()
    {
        $data = json_encode(array('documents' => $this->getDocumentsData()));

        $encoder = new JsonEncoder();
        $encoder
            ->node('documents', 'array')
                ->node('document', 'object')
                    ->node('name',        'scalar')->write('Test name one')->end()
                    ->node('description', 'scalar')->write('Test description one')->end()
                    ->node('rank',        'scalar')->write(11)->end()
                    ->node('rating',      'scalar')->write(93.31)->end()
                ->end()
                ->node('document', 'object')
                    ->node('name',        'scalar')->write('Test name two')->end()
                    ->node('description', 'scalar')->write('Test description two')->end()
                    ->node('rank',        'scalar')->write(11)->end()
                    ->node('rating',      'scalar')->write(93.31)->end()
                ->end();

        $this->assertJsonStringEqualsJsonString($data, $encoder->dump());
    }
}
