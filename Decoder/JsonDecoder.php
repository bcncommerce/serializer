<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Decoder;

class JsonDecoder extends ArrayDecoder
{
    /**
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct(json_decode($data, true));
    }
}
