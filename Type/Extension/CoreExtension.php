<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Type\Extension;

use Bcn\Component\Serializer\Format\XmlFormat;
use Bcn\Component\Serializer\Format\JsonFormat;
use Bcn\Component\Serializer\Format\FormatInterface;
use Bcn\Component\Serializer\Type\TextType;
use Bcn\Component\Serializer\Type\NumberType;
use Bcn\Component\Serializer\Type\DatetimeType;
use Bcn\Component\Serializer\Type\TypeInterface;

class CoreExtension implements ExtensionInterface
{
    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return array(
            new TextType(),
            new NumberType(),
            new DatetimeType(),
        );
    }

    /**
     * @return FormatInterface[]
     */
    public function getEncoders()
    {
        return array(
            new JsonFormat(),
            new XmlFormat(),
        );
    }
}
