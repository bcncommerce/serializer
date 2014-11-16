<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder\Csv;

use Bcn\Component\Serializer\Encoder\Streamer;

class CsvStreamer extends Streamer
{
    /** @var \Traversable */
    protected $iterator;

    /** @var string */
    protected $delimiter;

    /** @var string */
    protected $enclosure;

    /** @var string */
    protected $escape;

    /**
     * @param \Traversable $iterator
     * @param string       $delimiter
     * @param string       $enclosure
     * @param string       $escape
     */
    public function __construct($iterator, $delimiter = null, $enclosure = null, $escape = null)
    {
        $this->iterator  = $iterator;
        $this->delimiter = $delimiter ?: ";";
        $this->enclosure = $enclosure ?: "\"";
        $this->escape    = $escape    ?: "\\";
    }

    /**
     * @param callback $output
     */
    public function stream($output)
    {
        $isHeaderWritten = false;
        foreach ($this->iterator as $row) {
            if (!$isHeaderWritten) {
                $this->push(array_keys($row), $output);
                $isHeaderWritten = true;
            }

            $this->push($row, $output);
        }
    }

    /**
     * @param array    $row
     * @param callback $output
     */
    protected function push(array $row, $output)
    {
        $output($this->prepare($row));
    }

    /**
     * @param  array  $data
     * @return string
     */
    protected function prepare(array $data)
    {
        $stream = fopen('php://temp', 'rw');
        fputcsv($stream, $data, $this->delimiter, $this->enclosure);
        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        return $csv;
    }
}
