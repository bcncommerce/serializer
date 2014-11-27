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
        $format = $encoder->getName();

        if ($this->hasFormat($format)) {
            throw new \Exception("Format \"$format\" already defined");
        }

        $this->formats[$format] = $encoder;

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
     * @param  mixed      $origin
     * @param  Definition $definition
     * @param  mixed      $stream
     * @param  string     $format
     * @return mixed
     */
    public function encode($origin, Definition $definition, &$stream, $format)
    {
        return $this->getFormat($format)
            ->encode($origin, $definition, $stream);
    }

    /**
     * @param  mixed      $stream
     * @param  string     $format
     * @param  Definition $definition
     * @param  mixed      $origin
     * @return mixed
     */
    public function decode(&$stream, $format, Definition $definition, $origin = null)
    {
        return $this->getFormat($format)
            ->decode($stream, $definition, $origin);
    }
}
