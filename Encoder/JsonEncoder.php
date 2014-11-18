<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

class JsonEncoder extends ArrayEncoder
{
    /** @var int */
    protected $options = null;

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->options = $options;

        parent::__construct();
    }

    /**
     * @return array|string
     */
    public function dump()
    {
        return json_encode(parent::dump(), $this->options);
    }
}
