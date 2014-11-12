<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\TextType;

class TextTypeTest extends TestCase
{
    const TYPE_NAME        = 'text';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\ScalarNormalizer';

    public function testGetName()
    {
        $type = new TextType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $factory = $this->getFactoryMock();

        $type = new TextType();
        $normalizer = $type->build($factory);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
    }
}
