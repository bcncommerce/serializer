<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

use Bcn\Component\Serializer\Definition\Builder;
use Bcn\Component\Serializer\Resolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResolverTest extends TestCase
{
    public function testAddType()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolver = new Resolver();
        $resolver->addType($type);

        $this->assertTrue($resolver->hasType('foo'));
    }

    public function testAddTypeTwiceException()
    {
        $this->setExpectedException('Exception');

        $type = $this->getTypeMock();
        $type->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolver = new Resolver();
        $resolver->addType($type);
        $resolver->addType($type);
    }

    public function testGetTypeByName()
    {
        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolver = new Resolver();
        $resolver->addType($type);

        $this->assertSame($type, $resolver->getType('foo'));
    }

    public function testGetTypeByInstance()
    {
        $type = $this->getTypeMock();
        $type->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $resolver = new Resolver();
        $resolver->addType($type);

        $this->assertSame($type, $resolver->getType($type));
    }

    public function testGetTypeException()
    {
        $this->setExpectedException('Exception');

        $resolver = new Resolver();
        $resolver->getType('foo');
    }

    public function testGetDefinitionByName()
    {
        $options = array('foo' => 'bar');

        $type = $this->getTypeMock();
        $type->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('foo'));
        $type->expects($this->once())
            ->method('setDefaultOptions')
            ->will($this->returnCallback(function (OptionsResolverInterface $resolver) {
                $resolver->setDefaults(array('foo' => 'baz'));
            }));
        $type->expects($this->once())
            ->method('build')
            ->with($this->isInstanceOf('Bcn\Component\Serializer\Definition\Builder'), $options)
            ->will($this->returnCallback(function (Builder $builder) {
                $builder->node('test');
            }));

        $resolver = new Resolver();
        $resolver->addType($type);

        $definition = $resolver->getDefinition('foo', $options);

        $this->assertTrue($definition->hasProperty('test'));
    }

    public function testGetDefinitionByInstance()
    {
        $options = array('foo' => 'bar');

        $type = $this->getTypeMock();
        $type->expects($this->once())
            ->method('setDefaultOptions')
            ->will($this->returnCallback(function (OptionsResolverInterface $resolver) {
                $resolver->setDefaults(array('foo' => 'baz'));
            }));
        $type->expects($this->once())
            ->method('build')
            ->with($this->isInstanceOf('Bcn\Component\Serializer\Definition\Builder'), $options)
            ->will($this->returnCallback(function (Builder $builder) {
                $builder->node('test');
            }));

        $resolver = new Resolver();

        $definition = $resolver->getDefinition($type, $options);

        $this->assertTrue($definition->hasProperty('test'));
    }
}
