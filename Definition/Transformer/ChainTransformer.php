<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition\Transformer;

use Bcn\Component\Serializer\Definition\TransformerInterface;

class ChainTransformer implements TransformerInterface
{
    /** @var TransformerInterface[] */
    protected $transformers = array();

    /**
     * @param  TransformerInterface $transformer
     * @return $this
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @param  mixed $value
     * @param  mixed $origin
     * @return mixed
     */
    public function normalize($value, $origin)
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->normalize($value, $origin);
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
        foreach ($this->transformers as $transformer) {
            $value = $transformer->denormalize($value, $origin);
        }

        return $value;
    }
}
