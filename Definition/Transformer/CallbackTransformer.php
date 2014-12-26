<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition\Transformer;

use Bcn\Component\Serializer\Definition\TransformerInterface;

class CallbackTransformer implements TransformerInterface
{
    /** @var callback */
    protected $normalizer;

    /** @var callback */
    protected $denormalizer;

    /**
     * @param callback $normalizer
     * @param callback $denormalizer
     */
    public function __construct($normalizer = null, $denormalizer = null)
    {
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
    }

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function normalize($value, $origin)
    {
        if ($callback = $this->normalizer) {
            return $callback($value, $origin);
        }

        return $value;
    }

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function denormalize($value, $origin)
    {
        if ($callback = $this->denormalizer) {
            return $callback($value, $origin);
        }

        return $value;
    }
}
