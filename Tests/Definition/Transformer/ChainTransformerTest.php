<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Definition\Transformer;

use Bcn\Component\Serializer\Definition\Transformer\ChainTransformer;
use Bcn\Component\Serializer\Tests\TestCase;

class ChainTransformerTest extends TestCase
{
    public function testNormalize()
    {
        $transformerA = $this->getTransformerMock();
        $transformerA->expects($this->once())
            ->method('normalize')
            ->with('original')
            ->will($this->returnValue('pre-transformed'));

        $transformerB = $this->getTransformerMock();
        $transformerB->expects($this->once())
            ->method('normalize')
            ->with('pre-transformed')
            ->will($this->returnValue('transformed'));

        $transformer = new ChainTransformer();
        $transformer->addTransformer($transformerA);
        $transformer->addTransformer($transformerB);

        $result = $transformer->normalize('original');

        $this->assertEquals('transformed', $result);
    }

    public function testDenormalize()
    {
        $transformerA = $this->getTransformerMock();
        $transformerA->expects($this->once())
            ->method('denormalize')
            ->with('original')
            ->will($this->returnValue('pre-transformed'));

        $transformerB = $this->getTransformerMock();
        $transformerB->expects($this->once())
            ->method('denormalize')
            ->with('pre-transformed')
            ->will($this->returnValue('transformed'));

        $transformer = new ChainTransformer();
        $transformer->addTransformer($transformerA);
        $transformer->addTransformer($transformerB);

        $result = $transformer->denormalize('original');

        $this->assertEquals('transformed', $result);
    }
}
