<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Streamer;

use Bcn\Component\Serializer\Streamer\Streamer;

class FakeStreamer extends Streamer
{
    /** @var array */
    protected $parts = array();

    /**
     * @param array $parts
     */
    public function __construct(array $parts = array())
    {
        $this->parts = $parts;
    }

    /**
     * @param callable $output
     */
    public function stream($output)
    {
        foreach ($this->parts as $part) {
            $output($part);
        }
    }
}
