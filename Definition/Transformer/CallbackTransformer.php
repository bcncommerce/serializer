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
     * @param $value
     * @return mixed
     */
    public function normalize($value)
    {
        if ($callback = $this->normalizer) {
            return $callback($value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function denormalize($value)
    {
        if ($callback = $this->denormalizer) {
            return $callback($value);
        }

        return $value;
    }
}
