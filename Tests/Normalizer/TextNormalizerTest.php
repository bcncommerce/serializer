<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\TextNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class TextNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $normalizer = new TextNormalizer();
        $actual = $normalizer->normalize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testDenormalize()
    {
        $normalizer = new TextNormalizer();
        $actual = $normalizer->denormalize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testDenormalizeToVariable()
    {
        $normalizer = new TextNormalizer();
        $actual = null;
        $normalizer->denormalize('foo', $actual);

        $this->assertEquals('foo', $actual);
    }
}
