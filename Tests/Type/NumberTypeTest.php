<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberTypeTest extends TestCase
{
    const TYPE_NAME        = 'number';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\NumberNormalizer';

    public function testGetName()
    {
        $type = new NumberType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $options = array('decimals' => 0, 'decimal_point' => '.', 'thousand_separator' => '');
        $factory = $this->getTypeFactoryMock();

        $type = new NumberType();
        $normalizer = $type->build($factory, $options);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
    }

    public function testAllowedOptions()
    {
        $resolver = new OptionsResolver();
        $type = new NumberType();
        $type->setDefaultOptions($resolver);

        $resolver->resolve(array(
            'decimals'           => 4,
            'decimal_point'      => '.',
            'thousand_separator' => ' ',
        ));
    }

    public function testDefaultOptions()
    {
        $resolver = new OptionsResolver();
        $type = new NumberType();
        $type->setDefaultOptions($resolver);

        $options = $resolver->resolve(array());

        $this->assertEquals(array('decimals', 'decimal_point', 'thousand_separator'), array_keys($options));
    }
}
