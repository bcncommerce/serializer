<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

class CsvEncoder implements EncoderInterface
{
    const CONTEXT_NONE  = 'none';
    const CONTEXT_TABLE = 'table';
    const CONTEXT_LINE  = 'line';
    const CONTEXT_CELL  = 'cell';

    /** @var string */
    protected $context;

    /** @var resource */
    protected $stream;

    /** @var string */
    protected $cell = null;

    /** @var array */
    protected $line = null;

    /** @var array */
    protected $headers = null;

    /** @var string */
    protected $delimiter;

    /** @var string */
    protected $enclosure;

    /** @var string */
    protected $escape;

    /** @var bool */
    protected $isHeadersPushed = false;

    /**
     * @param resource $stream
     * @param array    $headers
     * @param string   $delimiter
     * @param string   $enclosure
     * @param string   $escape
     */
    public function __construct($stream, $headers = null, $delimiter = null, $enclosure = null, $escape = null)
    {
        $this->stream          = $stream;
        $this->headers         = $headers;
        $this->delimiter       = $delimiter ?: ";";
        $this->enclosure       = $enclosure ?: "\"";
        $this->escape          = $escape    ?: "\\";
        $this->isHeadersPushed = $headers === false;

        $this->context         = self::CONTEXT_NONE;
    }

    /**
     * Enter a node
     *
     * @param  string|null      $name
     * @param  string|null      $type
     * @return EncoderInterface
     * @throws \Exception
     */
    public function node($name = null, $type = null)
    {
        switch ($this->context) {
            case self::CONTEXT_NONE:
                $this->nodeTable($name, $type);
                break;
            case self::CONTEXT_TABLE:
                $this->nodeLine($name, $type);
                break;
            case self::CONTEXT_LINE:
                $this->nodeCell($name, $type);
                break;
            default:
                throw new \Exception(sprintf("Unable to open node \"%s\"", $name));
        }

        return $this;
    }

    /**
     * @param  string     $name
     * @param  string     $type
     * @throws \Exception
     */
    protected function nodeTable($name, $type)
    {
        if ($type != 'array') {
            throw new \Exception(sprintf("Type of the CSV table should be \"array\", \"%s\" given", $type));
        }

        $this->context = self::CONTEXT_TABLE;
    }

    /**
     * @param  string     $name
     * @param  string     $type
     * @throws \Exception
     */
    protected function nodeLine($name, $type)
    {
        if ($type != 'object') {
            throw new \Exception(sprintf("Type of lines in CSV table should be \"object\", \"%s\" given", $type));
        }

        $this->context = self::CONTEXT_LINE;
        $this->line    = array();
    }

    /**
     * @param  string     $name
     * @param  string     $type
     * @throws \Exception
     */
    protected function nodeCell($name, $type)
    {
        if ($type != 'scalar') {
            throw new \Exception(sprintf("Type of cells in CSV table should be \"scalar\", \"%s\" given", $type));
        }

        $this->cell = $name;
        $this->context = self::CONTEXT_CELL;
    }

    /**
     * Leave a node
     *
     * @return EncoderInterface
     * @throws \Exception
     */
    public function end()
    {
        switch ($this->context) {
            case self::CONTEXT_NONE:
                throw new \Exception("Out of context \"table\" context");
            case self::CONTEXT_TABLE:
                $this->context = self::CONTEXT_NONE;
                break;
            case self::CONTEXT_LINE:
                $this->context = self::CONTEXT_TABLE;
                $this->pushRow($this->line);
                break;
            case self::CONTEXT_CELL:
                $this->context = self::CONTEXT_LINE;
                break;
        }

        return $this;
    }

    /**
     * @param  string     $value
     * @return $this
     * @throws \Exception
     */
    public function write($value)
    {
        if ($this->context != self::CONTEXT_CELL) {
            throw new \Exception("Only \"cell\" context can be written");
        }

        $this->line[$this->cell] = $value;

        return $this;
    }

    /**
     * @param array $line
     */
    protected function pushRow(array $line)
    {
        if (!$this->isHeadersPushed) {
            $this->pushHeaders($line);
            $this->isHeadersPushed = true;
        }

        $this->push($this->map($line));
    }

    /**
     * @param array $line
     */
    protected function pushHeaders(array $line)
    {
        if ($this->headers === null) {
            $this->headers = array_keys($line);
        }

        $this->push($this->headers);
    }

    /**
     * @param  array $line
     * @return array
     */
    protected function map(array $line)
    {
        $data = array();
        foreach ($this->headers as $header) {
            $data[] = isset($line[$header]) ? $line[$header] : "";
        }

        return $data;
    }

    /**
     * @param array $fields
     */
    protected function push(array $fields)
    {
        fputcsv($this->stream, $fields, $this->delimiter, $this->enclosure);
    }
}
