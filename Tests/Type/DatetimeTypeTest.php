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
    const TYPE_NAME        = 'datetime';
    const NORMALIZER_CLASS = 'Bcn\Component\Serializer\Normalizer\DatetimeNormalizer';

    public function testGetName()
    {
        $type = new DatetimeType();

        $this->assertEquals(self::TYPE_NAME, $type->getName());
    }

    public function testBuild()
    {
        $options = array('format' => '');
        $factory = $this->getTypeFactoryMock();

        $type = new DatetimeType();
        $normalizer = $type->getNormalizer($factory, $options);

        $this->assertInstanceOf(self::NORMALIZER_CLASS, $normalizer);
    }

    public function testAllowedOptions()
    {
        $resolver = new OptionsResolver();
        $type = new DatetimeType();
        $type->setDefaultOptions($resolver);

        $resolver->resolve(array(
            'format' => \DateTime::ISO8601,
        ));
    }

    public function testDefaultOptions()
    {
        $resolver = new OptionsResolver();
        $type = new DatetimeType();
        $type->setDefaultOptions($resolver);

        $options = $resolver->resolve(array());

        $this->assertAvailableOptions(array('format'), $options);
    }
}
