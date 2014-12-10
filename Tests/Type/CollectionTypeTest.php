<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\CollectionType;
use Bcn\Component\Serializer\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeTest extends TestCase
{
    public function testGetName()
    {
        $type = new CollectionType();
        $this->assertEquals('collection', $type->getName());
    }

    public function testGetDefaultOptions()
    {
        $optionResolver = new OptionsResolver();
        $type = new CollectionType();
        $type->setDefaultOptions($optionResolver);
        $this->assertEquals(
            array('name', 'item_name', 'item_options', 'item_type'),
            array_keys($optionResolver->resolve(array('item_type' => 'text')))
        );
    }

    public function testBuild()
    {
        $prototype = $this->getBuilderMock();
        $prototype->expects($this->once())
            ->method('name')
            ->with('item');

        $builder = $this->getBuilderMock();
        $builder->expects($this->once())
            ->method('name')
            ->with('collection')
            ->will($this->returnSelf());
        $builder->expects($this->once())
            ->method('prototype')
            ->with('text', array('length' => 100))
            ->will($this->returnValue($prototype));

        $type = new CollectionType();
        $type->build($builder, array(
                'name'         => 'collection',
                'item_name'    => 'item',
                'item_type'    => 'text',
                'item_options' => array('length' => 100)
            ));
    }
}
