<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Integration\Type\Extension;

use Bcn\Component\Serializer\Tests\Integration\Type\DocumentArrayType;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentNestedType;
use Bcn\Component\Serializer\Tests\Integration\Type\DocumentType;
use Bcn\Component\Serializer\Type\Extension\TypeExtensionInterface;
use Bcn\Component\Serializer\Type\TypeInterface;

class DocumentTypeExtension implements TypeExtensionInterface
{
    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return array(
            new DocumentType(),
            new DocumentNestedType(),
            new DocumentArrayType(),
        );
    }
}
