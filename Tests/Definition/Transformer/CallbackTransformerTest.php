<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition\Transformer;

use Bcn\Component\Serializer\Definition\Transformer\CallbackTransformer;
use Bcn\Component\Serializer\Tests\TestCase;

class CallbackTransformerTest extends TestCase
{
    public function testNormalize()
    {
        $transformer = new CallbackTransformer(function ($x) { return $x.'foo'; }, function ($x) { return $x.'baz'; });
        $this->assertEquals('barfoo', $transformer->normalize('bar'));
    }

    public function testNormalizeNull()
    {
        $transformer = new CallbackTransformer();
        $this->assertEquals('bar', $transformer->normalize('bar'));
    }

    public function testDenormalize()
    {
        $transformer = new CallbackTransformer(function ($x) { return $x.'foo'; }, function ($x) { return $x.'baz'; });
        $this->assertEquals('barbaz', $transformer->denormalize('bar'));
    }

    public function testDenormalizeNull()
    {
        $transformer = new CallbackTransformer();
        $this->assertEquals('bar', $transformer->denormalize('bar'));
    }
}
