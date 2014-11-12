<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NumberNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class NumberType implements TypeInterface
{
    /**
     * @param  TypeFactory                          $factory
     * @param  array                                $options
     * @return NumberNormalizer|NormalizerInterface
     */
    public function build(TypeFactory $factory, array $options = array())
    {
        return new NumberNormalizer(
            isset($options['decimals']) ? $options['decimals']                       : 0,
            isset($options['decimal_point']) ? $options['decimal_point']             : '.',
            isset($options['thousands_separator']) ? $options['thousands_separator'] : ''
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'number';
    }
}
