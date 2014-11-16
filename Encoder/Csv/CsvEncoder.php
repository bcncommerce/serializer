<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder\Csv;

use Bcn\Component\Serializer\Encoder\CodecInterface;

class CsvEncoder implements CodecInterface
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
     * @param  array|\Traversable $data
     * @return mixed|CsvStreamer
     * @throws \Exception
     */
    public function encode($data)
    {
        return new CsvStreamer($data, $this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * @param  mixed             $data
     * @return mixed|CsvIterator
     */
    public function decode($data)
    {
        if (!is_resource($data)) {
            $data = fopen('data://text/plain;base64,'.base64_encode($data), 'r');
        }

        return new CsvIterator($data, $this->delimiter, $this->enclosure, $this->escape);
    }
}
