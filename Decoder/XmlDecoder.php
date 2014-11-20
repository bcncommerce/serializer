<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Decoder;

use Bcn\Component\Serializer\Exception\MalformedXmlException;

class XmlDecoder implements DecoderInterface
{
    /** @var \XMLReader */
    protected $reader;

    /** @var bool */
    protected $empty;

    /** @var bool */
    protected $finished = false;

    /**
     * @param string  $uri
     * @param string  $encoding
     * @param integer $options
     */
    public function __construct($uri, $encoding = null, $options = 0)
    {
        $this->reader = new \XMLReader();
        $this->reader->open($uri, $encoding, $options);
    }

    /**
     * @param  string|null $name
     * @param  string|null $type
     * @return bool
     */
    public function node($name = null, $type = null)
    {
        if ($this->empty) {
            return false;
        }

        $this->forward(array(\XMLReader::ELEMENT, \XMLReader::END_ELEMENT));

        if ($this->reader->nodeType != \XMLReader::ELEMENT) {
            return false;
        }

        if ($this->reader->localName != $name) {
            return false;
        }

        $this->empty = $this->reader->isEmptyElement;

        $this->fetch();

        return true;
    }

    /**
     * Get next node on the same level
     *
     * @return $this
     * @throws \Exception
     */
    public function next()
    {
        if (!$this->empty) {
            $this->forward(array(\XMLReader::ELEMENT, \XMLReader::END_ELEMENT));
        }

        return $this;
    }

    /**
     * Read a scalar value
     *
     * @return string|boolean|null|integer|float
     */
    public function read()
    {
        if ($this->empty) {
            return null;
        }

        return $this->reader->value;
    }

    /**
     * Check if current node is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->empty;
    }

    /**
     * Leave a node
     *
     * @return $this
     * @throws \Exception
     */
    public function end()
    {
        if ($this->empty) {
            $this->empty = false;
        } else {
            $this->forward(array(\XMLReader::END_ELEMENT));
            $this->fetch();
        }

        return $this;
    }

    /**
     * Skip all tokens until get one of $types at the current level
     *
     * @param  array      $types
     * @throws \Exception
     */
    protected function forward(array $types)
    {
        $deep = 0;

        while (!in_array($this->reader->nodeType, $types) || $deep != 0) {
            if ($this->reader->nodeType == \XMLReader::ELEMENT && !$this->reader->isEmptyElement) {
                $deep++;
            }

            if ($this->reader->nodeType == \XMLReader::END_ELEMENT) {
                $deep--;
            }

            $this->fetch();
        }
    }

    /**
     * @throws \Exception
     */
    protected function fetch()
    {
        if ($this->finished) {
            throw new \Exception("End of document");
        }

        $useInternalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        if (!@$this->reader->read()) {
            $this->finished = true;
            $errors = libxml_get_errors();

            if ($errors) {
                throw new MalformedXmlException($errors);
            }
        }

        libxml_use_internal_errors($useInternalErrors);
    }
}
