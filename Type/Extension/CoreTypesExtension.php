<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type\Extension;

use Bcn\Component\Serializer\Type\ArrayType;
use Bcn\Component\Serializer\Type\DatetimeType;
use Bcn\Component\Serializer\Type\IteratorType;
use Bcn\Component\Serializer\Type\NumberType;
use Bcn\Component\Serializer\Type\TextType;
use Bcn\Component\Serializer\Type\TypeInterface;

class CoreTypesExtension implements TypeExtensionInterface
{
    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return array(
            new TextType(),
            new NumberType(),
            new ArrayType(),
            new IteratorType(),
            new DatetimeType(),
        );
    }
}
