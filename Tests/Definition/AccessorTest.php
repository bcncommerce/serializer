<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition;

use Bcn\Component\Serializer\Definition\Accessor;
use Bcn\Component\Serializer\Tests\TestCase;

class AccessorTest extends TestCase
{
    public function testSetValueToNull()
    {
        $origin = null;
        $accessor = new Accessor($origin, '');
        $accessor->set('foo');

        $this->assertEquals('foo', $origin);
    }

    public function testSetValueToProperty()
    {
        $origin = $this->getDocument();
        $accessor = new Accessor($origin, 'name');
        $accessor->set('foo');

        $this->assertEquals('foo', $origin->getName());
    }

    public function testGetValue()
    {
        $origin   = 'foo';
        $accessor = new Accessor($origin, '');

        $this->assertEquals('foo', $accessor->get('foo'));
    }

    public function testGetPropertyValue()
    {
        $origin   = $this->getDocument('foo');
        $accessor = new Accessor($origin, 'name');

        $this->assertEquals('Test name foo', $accessor->get());
    }
}
