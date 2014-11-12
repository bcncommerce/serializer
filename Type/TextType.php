<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\ScalarNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class TextType implements TypeInterface
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return ScalarNormalizer|NormalizerInterface
     */
    public function build(TypeFactory $factory, array $options = array())
    {
        return new ScalarNormalizer();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'text';
    }
}
