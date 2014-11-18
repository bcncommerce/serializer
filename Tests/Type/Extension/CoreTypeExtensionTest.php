<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type\Extension;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\Extension\CoreTypesExtension;

class CoreTypeExtensionTest extends TestCase
{
    public function testGetTypes()
    {
        $extension = new CoreTypesExtension();

        foreach ($extension->getTypes() as $type) {
            $this->assertInstanceOf('Bcn\Component\Serializer\Type\TypeInterface', $type);
        }
    }
}
