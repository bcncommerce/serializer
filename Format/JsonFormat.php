<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format;

use Bcn\Component\Serializer\Definition;
use Bcn\Component\Serializer\Normalizer;

class JsonFormat implements FormatInterface
{
    /** @var int */
    protected $options = null;

    /** @var Normalizer */
    protected $normalizer;

    /**
     * @param int        $options
     * @param Normalizer $normalizer
     */
    public function __construct($options = null, Normalizer $normalizer = null)
    {
        $this->options    = $options;
        $this->normalizer = $normalizer ?: new Normalizer();
    }

    /**
     * @param mixed      $origin
     * @param Definition $definition
     * @param resource   $stream
     */
    public function encode($origin, Definition $definition, $stream)
    {
        $normalized = $this->normalizer->normalize($origin, $definition);
        $content = json_encode($normalized, $this->options);
        fwrite($stream, $content);
    }

    /**
     * @param  resource   $stream
     * @param  Definition $definition
     * @param  mixed      $origin
     * @return mixed
     */
    public function decode($stream, Definition $definition, &$origin = null)
    {
        $content = stream_get_contents($stream);
        $data    = json_decode($content, true);

        return $this->normalizer->denormalize($data, $definition, $origin);
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return array('json', 'application/json', 'text/json');
    }
}
