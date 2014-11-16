<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

class JsonEncoder implements EncoderDecoderInterface
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
     * @param  mixed $data
     * @return mixed
     */
    public function encode($data)
    {
        return json_encode($data, $this->options);
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
