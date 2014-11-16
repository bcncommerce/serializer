<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Csv;

use Bcn\Component\Serializer\Encoder\Csv\CsvEncoder;
use Bcn\Component\Serializer\Tests\TestCase;

class CsvEncoderTest extends TestCase
{
    public function testEncode()
    {
        $string = file_get_contents(__DIR__.'/files/encoding.csv');
        $data   = json_decode(file_get_contents(__DIR__.'/files/encoding.json'), true);

        $encoder = new CsvEncoder();
        $encoded = $encoder->encode($data);

        $this->assertEquals((string) $string, (string) $encoded);
    }

    public function testDecodeStream()
    {
        $stream = fopen(__DIR__.'/files/sample.csv', 'r');
        $data   = json_decode(file_get_contents(__DIR__.'/files/sample.json'), true);

        $encoder = new CsvEncoder();
        $iterator = $encoder->decode($stream);

        $this->assertEquals($data, iterator_to_array($iterator));
    }

    public function testDecodeString()
    {
        $string = file_get_contents(__DIR__.'/files/sample.csv');
        $data   = json_decode(file_get_contents(__DIR__.'/files/sample.json'), true);

        $encoder = new CsvEncoder();
        $iterator = $encoder->decode($string);

        $this->assertEquals($data, iterator_to_array($iterator));
    }
}
