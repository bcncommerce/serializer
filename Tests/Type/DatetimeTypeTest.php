<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\TestCase;
use Bcn\Component\Serializer\Type\DatetimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatetimeTypeTest extends TestCase
{
    public function testGetName()
    {
        $type = new DatetimeType();
        $this->assertEquals('datetime', $type->getName());
    }

    public function testGetDefaultOptions()
    {
        $optionResolver = new OptionsResolver();
        $type = new DatetimeType();
        $type->setDefaultOptions($optionResolver);
        $this->assertEquals(
            array('format'),
            array_keys($optionResolver->resolve(array()))
        );
    }

    public function testBuild()
    {
        $builder = $this->getBuilderMock();
        $builder->expects($this->once())
            ->method('transform')
            ->with($this->isInstanceOf('Bcn\Component\Serializer\Definition\Transformer\DatetimeTransformer'));

        $type = new DatetimeType();
        $type->build($builder, array('format' => '.'));
    }
}
