<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder\Csv;

use Bcn\Component\Serializer\Encoder\EncoderDecoderInterface;

class CsvEncoder implements EncoderDecoderInterface
{
    /** @var string */
    protected $delimiter;

    /** @var string */
    protected $enclosure;

    /** @var string */
    protected $escape;

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = null, $enclosure = null, $escape = null)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function encode($data)
    {
        return null;
    }

    /**
     * @param  mixed $data
     * @return mixed
     */
    public function decode($data)
    {
        return new CsvIterator($data);
    }
}
