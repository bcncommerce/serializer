<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder\Csv;

class CsvIterator implements \Iterator
{
    /** @var resource */
    protected $stream;

    /** @var array */
    protected $headers = array();

    /** @var array */
    protected $row = array();

    /** @var string */
    protected $delimiter;

    /** @var string */
    protected $enclosure;

    /** @var string */
    protected $escape;

    /** @var int */
    protected $dataPosition = 0;

    /** @var int */
    protected $line = 0;

    /**
     * @param resource $stream
     * @param null     $delimiter
     * @param null     $enclosure
     * @param null     $escape
     */
    public function __construct($stream, $delimiter = null, $enclosure = null, $escape = null)
    {
        $this->stream = $stream;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;

        $this->rewind($stream);
    }

    /**
     * Return current line from file
     * @return array|mixed
     */
    public function current()
    {
        return $this->row;
    }

    /**
     * Fetch next CSV line
     */
    public function next()
    {
        $length = count($this->headers);
        $raw = array_slice(array_pad($this->fetch(), $length, null), 0, $length);
        $this->row = array_combine($this->headers, $raw);

        $this->line++;
    }

    /**
     * Return CSV line number
     * @return int
     */
    public function key()
    {
        return $this->line;
    }

    /**
     * Check if current position is valid
     * @return bool
     */
    public function valid()
    {
        return !feof($this->stream);
    }

    /**
     * Rewind back to first row
     */
    public function rewind()
    {
        fseek($this->stream, $this->dataPosition, SEEK_SET);

        if ($this->dataPosition === 0) {
            $this->headers = $this->fetch();
            $this->dataPosition = intval(ftell($this->stream));
        }

        $this->line = 0;
    }

    /**
     * @return array
     */
    protected function fetch()
    {
        return fgetcsv($this->stream, 10000, $this->delimiter, $this->enclosure, $this->escape);
    }
}
