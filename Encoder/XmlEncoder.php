<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Encoder;

class XmlEncoder implements EncoderInterface
{
    /** @var \XMLWriter */
    protected $writer;

    /** @var resource */
    protected $stream;

    /** @var bool */
    protected $finalised;

    /** @var bool */
    protected $initialised;

    /** @var string */
    protected $version;

    /** @var string */
    protected $encoding;

    /** @var int */
    protected $deep = 0;

    /**
     * @param string|resource $stream
     * @param string          $version
     * @param string          $encoding
     */
    public function __construct($stream, $version = null, $encoding = null)
    {
        $this->version     = $version;
        $this->encoding    = $encoding;
        $this->finalised   = false;
        $this->initialised = false;
        $this->deep        = 0;

        $this->writer = new \XMLWriter();

        if (is_resource($stream)) {
            $this->stream = $stream;
            $this->writer->openMemory();
        } else {
            $this->stream = null;
            $this->writer->openUri($stream);
        }
    }

    /**
     * @param  string $version
     * @param  string $encoding
     * @return $this
     */
    protected function initialise($version = null, $encoding = null)
    {
        $this->writer->setIndent(true);
        $this->writer->setIndentString("    ");
        $this->writer->startDocument($version ?: "1.0", $encoding ?: "UTF-8");

        $this->initialised = true;

        return $this;
    }

    /**
     * @param  string|null      $name
     * @param  string|null      $type
     * @return EncoderInterface
     */
    public function node($name = null, $type = null)
    {
        if ($this->deep == 0) {
            $this->initialise($this->version, $this->encoding);
        }

        $this->writer->startElement($name ?: "element");
        $this->flush();

        $this->deep++;

        return $this;
    }

    /**
     * @param  string           $value
     * @return EncoderInterface
     * @throws \Exception
     */
    public function write($value)
    {
        if (!$this->initialised) {
            throw new \Exception("Document does not have root node");
        }

        if ($value === null) {
            return $this;
        }

        if (is_numeric($value)) {
            $this->writer->writeRaw($value);
        } else {
            $this->writer->writeCdata($value);
        }

        $this->flush();

        return $this;
    }

    /**
     * @return EncoderInterface
     */
    public function end()
    {
        $this->writer->endElement();
        $this->flush();

        $this->deep--;
        if ($this->deep == 0) {
            $this->finalise();
        }

        return $this;
    }

    /**
     *
     */
    protected function flush()
    {
        if ($this->finalised) {
            throw new \Exception("Document already finalised, it can not be extended");
        }

        if ($this->stream) {
            fwrite($this->stream, $this->writer->outputMemory(true));
        } else {
            $this->writer->flush(true);
        }
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function dump()
    {
        if (!$this->stream) {
            throw new \Exception("Can not dump document");
        }

        rewind($this->stream);

        return stream_get_contents($this->stream);
    }

    /**
     * @return $this
     */
    public function finalise()
    {
        $this->writer->endDocument();
        $this->flush();

        $this->finalised = true;

        return $this;
    }
}
