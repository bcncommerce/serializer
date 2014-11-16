<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Streamer;

use Bcn\Component\Serializer\Streamer\BufferStreamer;
use Bcn\Component\Serializer\Tests\TestCase;

class BufferStreamerTest extends TestCase
{
    public function testStream()
    {
        $output = "";

        $streamer = new BufferStreamer("content");
        $streamer->stream(function ($data) use (&$output) {
            $output .= $data;
        });

        $this->assertEquals('content', $output);
    }

    public function testDump()
    {
        $streamer = new BufferStreamer("content");

        $this->assertEquals('content', (string) $streamer);
    }
}
