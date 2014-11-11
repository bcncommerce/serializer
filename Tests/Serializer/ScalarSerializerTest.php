<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Serializer;

use Bcn\Component\Serializer\Serializer\ScalarSerializer;
use Bcn\Component\Serializer\Tests\TestCase;

class ScalarSerializerTest extends TestCase
{
    public function testSerialize()
    {
        $serializer = new ScalarSerializer();
        $actual = $serializer->serialize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testUnserialize()
    {
        $serializer = new ScalarSerializer();
        $actual = $serializer->unserialize('foo');

        $this->assertEquals('foo', $actual);
    }

    public function testUnserializeToVariable()
    {
        $serializer = new ScalarSerializer();
        $actual = null;
        $serializer->unserialize('foo', $actual);

        $this->assertEquals('foo', $actual);
    }
}
