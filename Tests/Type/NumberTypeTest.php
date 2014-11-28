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

    public function testBuild()
    {
        $builder = $this->getBuilderMock();
        $builder->expects($this->once())
            ->method('transform')
            ->with($this->isInstanceOf('Bcn\Component\Serializer\Definition\Transformer\NumberTransformer'));

        $type = new NumberType();
        $type->build($builder, array('decimals' => 1, 'decimal_point' => ',', 'thousand_separator' => '.'));
    }
}
