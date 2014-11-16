<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer;

use Bcn\Component\Serializer\Normalizer\Iterator\DenormalizeIterator;
use Bcn\Component\Serializer\Normalizer\Iterator\NormalizeIterator;

class IteratorNormalizer implements NormalizerInterface
{
    /** @var NormalizerInterface */
    protected $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param  object            $object
     * @return NormalizeIterator
     */
    public function normalize($object)
    {
        return new NormalizeIterator($object, $this->normalizer);
    }

    /**
     * @param  mixed               $data
     * @param  object              $object
     * @return DenormalizeIterator
     */
    public function denormalize($data, &$object = null)
    {
        $object = new DenormalizeIterator($data, $this->normalizer);

        return $object;
    }

    /**
     * @return NormalizerInterface
     */
    public function getItemNormalizer()
    {
        return $this->normalizer;
    }
}
