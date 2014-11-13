<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type\Extension;

use Bcn\Component\Serializer\Type\TypeInterface;

interface TypeExtensionInterface
{
    /**
     * @return TypeInterface[]
     */
    public function getTypes();
}
