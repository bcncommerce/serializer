<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\NumberType;

class NumberTypeTest extends TestCase
{
    const TYPE_NAME        = 'number';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\NumberNormalizer';

    public function testGetName()
    {
        $type = new NumberType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $factory = $this->getTypeFactoryMock();

        $type = new NumberType();
        $normalizer = $type->build($factory);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
    }
}
