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

    /**
     * @param resource $stream
     */
    public function __construct($stream, $root, $version = null, $encoding = null)
    {
        $this->stream    = $stream;
        $this->finalised = false;

        $this->writer = new \XMLWriter();
        $this->writer->openMemory();

        $this->initialise($root, $version, $encoding);
    }

    /**
     * @param  string $root
     * @param  string $version
     * @param  string $encoding
     * @return $this
     */
    protected function initialise($root, $version = null, $encoding = null)
    {
        $this->writer->setIndent(true);
        $this->writer->setIndentString("    ");
        $this->writer->startDocument($version ?: "1.0", $encoding ?: "UTF-8");
        $this->writer->startElement($root);

        return $this;
    }

    /**
     * @param  string|null      $name
     * @param  string|null      $type
     * @return EncoderInterface
     */
    public function node($name = null, $type = null)
    {
        $this->writer->startElement($name ?: "element");
        $this->flush();

        return $this;
    }

    /**
     * @param  string           $value
     * @return EncoderInterface
     */
    public function write($value)
    {
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

        fwrite($this->stream, $this->writer->outputMemory(true));
    }

    /**
     * @return string
     */
    public function dump()
    {
        $this->finalise();

        rewind($this->stream);

        return stream_get_contents($this->stream);
    }

    /**
     * @return $this
     */
    public function finalise()
    {
        if (!$this->finalised) {
            $this->writer->endElement();
            $this->writer->endDocument();
            $this->flush();
        }

        $this->finalised = true;

        return $this;
    }
}
