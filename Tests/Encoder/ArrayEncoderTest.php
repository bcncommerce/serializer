<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder;

use Bcn\Component\Serializer\Encoder\ArrayEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class ArrayEncoderTest extends TestCase
{
    public function testEncodeStructure()
    {
        $data = array(
            'first' => 'one',
            'second' => 'two',
        );

        $encoder = new ArrayEncoder();
        $encoder->node('first', 'scalar')->write('one')->end();
        $encoder->node('second', 'scalar')->write('two')->end();

        $this->assertEquals($data, $encoder->dump());
    }

    public function testEncodeArray()
    {
        $data = array(
            'one',
            'two',
        );

        $encoder = new ArrayEncoder();
        $encoder->node(null, 'scalar')->write('one')->end();
        $encoder->node(null, 'scalar')->write('two')->end();

        $this->assertEquals($data, $encoder->dump());
    }

    public function testEncodeScalar()
    {
        $encoder = new ArrayEncoder();
        $encoder->write('foo');

        $this->assertEquals('foo', $encoder->dump());
    }

    public function testEncodeDocumentArray()
    {
        $data = $this->getDocumentsData();

        $encoder = new ArrayEncoder();
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

        $this->assertEquals(array('documents' => $data), $encoder->dump());
    }
}
