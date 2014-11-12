<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\TypeFactory;

class TypeFactoryTest extends TestCase
{
    public function testCreateByName()
    {
        $factory = new TypeFactory();
        $normalizer = $this->getNormalizerMock();
        $options = array();

        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));
        $type->expects($this->once())
            ->method('build')
            ->with($this->equalTo($factory), $this->equalTo($options))
            ->will($this->returnValue($normalizer));

        $factory->addType($type);

        $actual = $factory->create('test', $options);

        $this->assertSame($normalizer, $actual);
    }

    public function testCreateByInstance()
    {
        $factory = new TypeFactory();
        $normalizer = $this->getNormalizerMock();
        $options = array();

        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('build')
            ->with($this->equalTo($factory), $this->equalTo($options))
            ->will($this->returnValue($normalizer));

        $actual = $factory->create($type, $options);

        $this->assertSame($normalizer, $actual);
    }

    public function testCreateUnknownTypeException()
    {
        $this->setExpectedException('Exception');

        $factory = new TypeFactory();
        $factory->create('test');
    }

    public function testGetType()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new TypeFactory();
        $factory->addType($type);

        $this->assertSame($type, $factory->getType('test'));
    }

    public function testGetTypeException()
    {
        $this->setExpectedException('Exception');

        $factory = new TypeFactory();
        $factory->getType('test');
    }

    public function testHasTypeByName()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new TypeFactory();
        $factory->addType($type);

        $this->assertTrue($factory->hasType('test'));
    }

    public function testAddTypeException()
    {
        $this->setExpectedException('Exception');

        $type = $this->getTypeMock();
        $type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new TypeFactory();
        $factory->addType($type);
        $factory->addType($type);
    }

    public function testDoesNotHaveTypeByName()
    {
        $factory = new TypeFactory();

        $this->assertFalse($factory->hasType('test'));
    }

    public function testHasTypeByInstance()
    {
        $type = $this->getTypeMock();
        $type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new TypeFactory();
        $factory->addType($type);

        $this->assertTrue($factory->hasType($type));
    }

    public function testDoesNotHaveTypeByInstance()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('test'));

        $factory = new TypeFactory();

        $this->assertFalse($factory->hasType($type));
    }
}
