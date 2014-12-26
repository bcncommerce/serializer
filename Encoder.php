<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer;

use Bcn\Component\Serializer\Format\FormatInterface;

class Encoder
{
    /** @var FormatInterface[] */
    protected $formats = array();

    /**
     * @param  FormatInterface $encoder
     * @return $this
     * @throws \Exception
     */
    public function addFormat(FormatInterface $encoder)
    {
        $names = $encoder->getNames();
        foreach ($names as $name) {
            if ($this->hasFormat($name)) {
                throw new \Exception("Format \"$name\" already defined");
            }

            $this->formats[$name] = $encoder;
        }

        return $this;
    }

    /**
     * @param  string $format
     * @return bool
     */
    public function hasFormat($format)
    {
        return isset($this->formats[$format]);
    }

    /**
     * @param  string          $format
     * @return FormatInterface
     * @throws \Exception
     */
    public function getFormat($format)
    {
        if (!$this->hasFormat($format)) {
            throw new \Exception("Format \"$format\" is not defined");
        }

        return $this->formats[$format];
    }

    /**
     * @param  mixed       $origin
     * @param  Definition  $definition
     * @param  mixed       $stream
     * @param  string      $format
     * @return string|bool
     */
    public function encode($origin, Definition $definition, $stream, $format)
    {
        $returnContent = $stream === false;
        $encoder = $this->getFormat($format);

        if ($returnContent) {
            $stream = fopen("php://temp", "rw+");
        }

        $encoder->encode($origin, $definition, $stream);

        if (!$returnContent) {
            return false;
        }

        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);

        return $content;
    }

    /**
     * @param  mixed      $stream
     * @param  string     $format
     * @param  Definition $definition
     * @param  mixed      $origin
     * @return mixed
     */
    public function decode($stream, $format, Definition $definition, $origin = null)
    {
        $fromContent = is_string($stream);
        if ($fromContent) {
            $content = $stream;
            $stream = fopen('php://temp', 'rw+');
            fputs($stream, $content);
            rewind($stream);
        }

        $object = $this->getFormat($format)
            ->decode($stream, $definition, $origin);

        if ($fromContent) {
            fclose($stream);
        }

        return $object;
    }
}
