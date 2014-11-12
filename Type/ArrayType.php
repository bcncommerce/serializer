<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\ArrayNormalizer;
use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class ArrayType implements TypeInterface
{
    /**
     * @param  TypeFactory                         $factory
     * @param  array                               $options
     * @return ArrayNormalizer|NormalizerInterface
     */
    public function build(TypeFactory $factory, array $options = array())
    {
        return new ArrayNormalizer(
            $factory->create($options['item_type'], $options['item_options'])
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'array';
    }
}
