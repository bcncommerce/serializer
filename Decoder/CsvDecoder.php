<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Decoder;

class CsvDecoder implements DecoderInterface
{
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

    /**
     * @param resource $stream
     * @param array    $headers
     * @param string   $delimiter
     * @param string   $enclosure
     * @param string   $escape
     */
    public function __construct($stream, $headers = null, $delimiter = null, $enclosure = null, $escape = null)
    {
        $this->stream    = $stream;
        $this->headers   = $headers;
        $this->delimiter = $delimiter ?: ";";
        $this->enclosure = $enclosure ?: "\"";
        $this->escape    = $escape    ?: "\\";

        $this->context   = self::CONTEXT_TABLE;

        $this->initialize();
    }

    /**
     * @throws \Exception
     */
    protected function initialize()
    {
        if ($this->headers === null) {
            $this->headers = $this->fetch();
        }

        $this->next();
    }

    /**
     * Enter a node
     *
     * @param  string|null      $name
     * @param  string|null      $type
     * @return DecoderInterface
     * @throws \Exception
     */
    public function node($name = null, $type = null)
    {
        switch ($this->context) {
            case self::CONTEXT_TABLE:
                $this->nodeLine($name, $type);
                break;
            case self::CONTEXT_LINE:
                $this->nodeCell($name, $type);
                break;
            default:
                throw new \Exception(sprintf("Unable to read node \"%s\"", $name));
        }

        return $this;
    }

    /**
     * Check current pointer in valid position
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->context != self::CONTEXT_TABLE || $this->line;
    }

    /**
     * Get next node on the same level
     *
     * @return boolean
     * @throws \Exception
     */
    public function next()
    {
        if ($this->context != self::CONTEXT_TABLE) {
            throw new \Exception(
                sprintf("Next line can be fetched only in \"table\" context, current \"%s\"", $this->context)
            );
        }

        $raw = $this->fetch();
        if ($raw) {
            $length = count($this->headers);
            $raw = array_slice(array_pad($raw, $length, null), 0, $length);
            $this->line = array_combine($this->headers, $raw);
        } else {
            $this->line = null;
        }
    }

    /**
     * Check if node exists
     *
     * @param  string     $name
     * @return boolean
     * @throws \Exception
     */
    public function exists($name)
    {
        if ($this->context != self::CONTEXT_LINE) {
            throw new \Exception(sprintf("Context \"%s\" can not have any children", $this->context));
        }

        return in_array($name, $this->headers);
    }

    /**
     * Read a scalar value
     *
     * @return string|boolean|null|integer|float
     * @throws \Exception
     */
    public function read()
    {
        if ($this->context != self::CONTEXT_CELL) {
            throw new \Exception(sprintf("Context \"%s\" can not be read", $this->context));
        }

        return isset($this->line[$this->cell]) ? $this->line[$this->cell] : null;
    }

    /**
     * Leave a node
     *
     * @return DecoderInterface
     * @throws \Exception
     */
    public function end()
    {
        switch ($this->context) {
            case self::CONTEXT_TABLE:
                throw new \Exception("Out of context \"table\" context");
                break;
            case self::CONTEXT_LINE:
                $this->context = self::CONTEXT_TABLE;
                break;
            case self::CONTEXT_CELL:
                $this->context = self::CONTEXT_LINE;
                break;
        }

        return $this;
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
     * @return array
     */
    protected function fetch()
    {
        do {
            $raw = fgetcsv($this->stream, 10000, $this->delimiter, $this->enclosure, $this->escape);
        } while ((!is_array($raw) || !count($raw)) && !feof($this->stream));

        return $raw;
    }
}
