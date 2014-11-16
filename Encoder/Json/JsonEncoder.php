<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder\Json;

use Bcn\Component\Serializer\Encoder\CodecInterface;
use Bcn\Component\Serializer\Streamer\BufferStreamer;
use Bcn\Component\Serializer\Streamer\StreamerInterface;

class JsonEncoder implements CodecInterface
{
    /** @var integer */
    protected $options;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->options = $options;
    }

    /**
     * @param  mixed             $data
     * @return StreamerInterface
     */
    public function encode($data)
    {
        return new BufferStreamer(json_encode($data, $this->options));
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function decode($data)
    {
        return json_decode($data, true);
    }
}
