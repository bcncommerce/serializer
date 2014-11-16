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
        $encoded = file_get_contents(__DIR__.'/files/encoding.csv');
        $decoded = json_decode(file_get_contents(__DIR__.'/files/encoding.json'), true);

        $encoder = new CsvEncoder();
        $streamer = $encoder->encode($decoded);

        $this->assertInstanceOf('Bcn\Component\Serializer\Streamer\StreamerInterface', $streamer);
        $this->assertEquals((string) $encoded, (string) $streamer);
    }

    public function testDecodeStream()
    {
        $stream  = fopen(__DIR__.'/files/sample.csv', 'r');
        $decoded = json_decode(file_get_contents(__DIR__.'/files/sample.json'), true);

        $encoder = new CsvEncoder();
        $iterator = $encoder->decode($stream);

        $this->assertEquals($decoded, iterator_to_array($iterator));
    }

    public function testDecodeString()
    {
        $encoded = file_get_contents(__DIR__.'/files/sample.csv');
        $decoded = json_decode(file_get_contents(__DIR__.'/files/sample.json'), true);

        $encoder = new CsvEncoder();
        $iterator = $encoder->decode($encoded);

        $this->assertEquals($decoded, iterator_to_array($iterator));
    }
}
