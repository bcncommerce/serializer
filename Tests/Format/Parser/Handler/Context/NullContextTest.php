<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Parser\Handler\Context;

use Bcn\Component\Serializer\Format\Parser\Handler\Context\NullContext;
use Bcn\Component\Serializer\Tests\TestCase;

class NullContextTest extends TestCase
{
    public function testNoErrors()
    {
        $context = new NullContext();

        $context->append("");
        $context->start("foo");
        $context->end("foo", "baz");
        $context->fetch();
        $context->reset();
    }

    public function testFetchedDataIsNull()
    {
        $context = new NullContext();
        $this->assertNull($context->fetch());
    }

    public function testSubContextIsNullContext()
    {
        $context = new NullContext();

        $subContext = $context->start("foo");

        $this->assertInstanceOf(get_class($context), $subContext);
    }
}
