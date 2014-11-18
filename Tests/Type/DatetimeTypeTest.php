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
    public function testGetSerializer()
    {
        $type = new DatetimeType();
        $serializer = $type->getSerializer($this->getTypeFactoryMock());

        $this->assertInstanceOf('Bcn\Component\Serializer\Serializer\ScalarSerializer', $serializer);
    }

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

    /**
     * @dataProvider provideNormalizedAndDenormalized
     */
    public function testNormalize($denormalized, $normalized, $options)
    {
        $optionResolver = new OptionsResolver();

        $type = new DatetimeType();
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

        $type = new DatetimeType();
        $type->setDefaultOptions($optionResolver);
        $serializer = $type->getSerializer($this->getTypeFactoryMock(), $optionResolver->resolve($options));

        $denormalizer = $serializer->getDenormalizer();

        $this->assertEquals($denormalized, $denormalizer($normalized));
    }

    public function provideNormalizedAndDenormalized()
    {
        $date = new \DateTime('Mon Jan 4 14:26:20 2010 +0000');

        return array(
            'ISO8601'  => array($date, '2010-01-04T14:26:20+0000',            array('format' => 'Y-m-d\TH:i:sO')),
            'Cookie'   => array($date, 'Monday, 04-Jan-10 14:26:20 GMT+0000', array('format' => 'l, d-M-y H:i:s T')),
            'Unixtime' => array($date, '1262615180',                          array('format' => 'U')),
        );
    }
}
