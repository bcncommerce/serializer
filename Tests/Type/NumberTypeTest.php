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
    public function testGetSerializer()
    {
        $type = new NumberType();
        $serializer = $type->getSerializer($this->getTypeFactoryMock());

        $this->assertInstanceOf('Bcn\Component\Serializer\Serializer\ScalarSerializer', $serializer);
    }

    public function testGetName()
    {
        $type = new NumberType();

        $this->assertEquals('number', $type->getName());
    }

    public function testGetDefaultOptions()
    {
        $optionResolver = new OptionsResolver();

        $type = new NumberType();
        $type->setDefaultOptions($optionResolver);

        $this->assertEquals(
            array('decimals', 'decimal_point', 'thousand_separator'),
            array_keys($optionResolver->resolve(array()))
        );
    }

    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testNormalize($denormalized, $normalized, $options)
    {
        $optionResolver = new OptionsResolver();

        $type = new NumberType();
        $type->setDefaultOptions($optionResolver);
        $serializer = $type->getSerializer($this->getTypeFactoryMock(), $optionResolver->resolve($options));

        $normalizer = $serializer->getNormalizer();

        $this->assertEquals($normalized, $normalizer($denormalized));
    }

    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testDenormalize($denormalized, $normalized, $options)
    {
        $optionResolver = new OptionsResolver();

        $type = new NumberType();
        $type->setDefaultOptions($optionResolver);
        $serializer = $type->getSerializer($this->getTypeFactoryMock(), $optionResolver->resolve($options));

        $denormalizer = $serializer->getDenormalizer();

        $this->assertEquals($denormalized, $denormalizer($normalized));
    }

    public function provideNormalizedAndDenormalized()
    {
        return array(
            'Integer' => array(
                1022,
                '1022',
                array('decimals' => 0, 'decimal_point' => '.', 'thousand_separator' => ''),
            ),
            'Integer with thousand separator' => array(
                1022000,
                '1*022*000',
                array('decimals' => 0, 'decimal_point' => '.', 'thousand_separator' => '*'),
            ),
            'Float' => array(
                102.2,
                '102.2',
                array('decimals' => 1, 'decimal_point' => '.', 'thousand_separator' => ''),
            ),
            'Float with decimal separator' => array(
                102.2,
                '102#2',
                array('decimals' => 1, 'decimal_point' => '#', 'thousand_separator' => '*'),
            ),
            'Float with thousand separator' => array(
                11202.2,
                '11*202#2',
                array('decimals' => 1, 'decimal_point' => '#', 'thousand_separator' => '*'),
            ),
        );
    }
}
