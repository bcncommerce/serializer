<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Normalizer;

use Bcn\Component\Serializer\Normalizer\ScalarNormalizer;
use Bcn\Component\Serializer\Tests\TestCase;

class ScalarNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $normalizer = new ScalarNormalizer();
        $actual = $normalizer->normalize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testDenormalize()
    {
        $normalizer = new ScalarNormalizer();
        $actual = $normalizer->denormalize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testDenormalizeToVariable()
    {
        $normalizer = new ScalarNormalizer();
        $actual = null;
        $normalizer->denormalize('foo', $actual);

        $this->assertEquals('foo', $actual);
    }
}
