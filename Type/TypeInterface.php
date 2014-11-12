<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

interface TypeInterface
{
    /**
     * @param  TypeFactory         $factory
     * @param  array               $options
     * @return NormalizerInterface
     */
    public function build(TypeFactory $factory, array $options = array());

    /**
     * @return string
     */
    public function getName();
}
