<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type\Extension;

use Bcn\Component\Serializer\Format\FormatInterface;
use Bcn\Component\Serializer\Tests\Type\AttributesType;
use Bcn\Component\Serializer\Tests\Type\DocumentArrayType;
use Bcn\Component\Serializer\Tests\Type\DocumentType;
use Bcn\Component\Serializer\Type\Extension\ExtensionInterface;
use Bcn\Component\Serializer\Type\TypeInterface;

class TestExtension implements ExtensionInterface
{
    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return array(
            new DocumentType(),
            new AttributesType(),
            new DocumentArrayType(),
        );
    }

    /**
     * @return FormatInterface[]
     */
    public function getEncoders()
    {
        return array();
    }
}
