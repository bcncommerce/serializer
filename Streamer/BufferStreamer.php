<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Streamer;

class BufferStreamer implements StreamerInterface
{
    /** @var string */
    protected $content;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param callback $output
     */
    public function stream($output)
    {
        $output($this->content);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}
