<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Streamer;

use Bcn\Component\Serializer\Tests\TestCase;

class StreamerTest extends TestCase
{
    public function testStream()
    {
        $output = "";

        $streamer = new FakeStreamer(array('fa', 'ke'));
        $streamer->stream(function ($data) use (&$output) {
            $output .= $data;
        });

        $this->assertEquals('fake', $output);
    }

    public function testDump()
    {
        $streamer = new FakeStreamer(array('fa', 'ke'));

        $this->assertEquals('fake', (string) $streamer);
    }
}
