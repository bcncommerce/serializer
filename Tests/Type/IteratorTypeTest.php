<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\IteratorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IteratorTypeTest extends TestCase
{
    const TYPE_NAME        = 'iterator';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\IteratorNormalizer';

    public function testGetName()
    {
        $type = new IteratorType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $itemNormalizer = $this->getNormalizerMock();

        $factory = $this->getTypeFactoryMock();
        $factory->expects($this->once())
            ->method('create')
            ->with($this->equalTo('text'))
            ->will($this->returnValue($itemNormalizer));

        $options = array('item_type' => 'text', 'item_options' => array());

        $type = new IteratorType();
        $normalizer = $type->getNormalizer($factory, $options);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
        $this->assertSame($itemNormalizer, $normalizer->getItemNormalizer());
    }

    public function testAllowedOptions()
    {
        $resolver = new OptionsResolver();
        $type = new IteratorType();
        $type->setDefaultOptions($resolver);

        $resolver->resolve(array(
            'item_type'    => 'text',
            'item_options' => array(),
        ));
    }

    public function testDefaultOptions()
    {
        $resolver = new OptionsResolver();
        $type = new IteratorType();
        $type->setDefaultOptions($resolver);

        $options = $resolver->resolve(array('item_type' => 'text'));

        $this->assertAvailableOptions(array('item_options', 'item_type'), $options);
    }
}
