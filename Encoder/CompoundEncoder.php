<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

use Bcn\Component\Serializer\Streamer\StreamerInterface;

class CompoundEncoder implements EncoderDecoderInterface
{
    /** @var EncoderInterface */
    protected $encoder;

    /** @var DecoderInterface */
    protected $decoder;

    /**
     * @param EncoderInterface $encoder
     * @param DecoderInterface $decoder
     */
    public function __construct(EncoderInterface $encoder, DecoderInterface $decoder)
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    /**
     * @param  mixed             $data
     * @return StreamerInterface
     */
    public function encode($data)
    {
        return $this->encoder->encode($data);
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function decode($data)
    {
        return $this->decoder->decode($data);
    }
}
