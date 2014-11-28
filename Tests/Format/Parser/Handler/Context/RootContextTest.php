<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Encoder\Parser\Handler\Context;

use Bcn\Component\Serializer\Format\Parser\Handler\Context\RootContext;
use Bcn\Component\Serializer\Tests\TestCase;

class RootContextTest extends TestCase
{
    public function testStart()
    {
        $context = $this->getParserContextMock();
        $root = new RootContext($context);

        $this->assertSame($context, $root->start('root'));
    }

    public function testEnd()
    {
        $context = $this->getParserContextMock();
        $root = new RootContext($context);
        $root->end('root', null);
    }

    public function testAppend()
    {
        $context = $this->getParserContextMock();
        $root = new RootContext($context);
        $root->append('');
    }

    public function testReset()
    {
        $context = $this->getParserContextMock();
        $root = new RootContext($context);
        $root->reset();
    }

    public function testFetch()
    {
        $fetched = new \stdClass();
        $context = $this->getParserContextMock();
        $context->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($fetched));

        $root = new RootContext($context);

        $this->assertSame($fetched, $root->fetch());
    }

    public function testRootNodeNameMismatchException()
    {
        $this->setExpectedException('Exception');

        $context = $this->getParserContextMock();
        $root = new RootContext($context, 'root');
        $root->start('document');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getParserContextMock()
    {
        return $this->getMock('Bcn\Component\Serializer\Format\Parser\Handler\Context\ContextInterface');
    }
}
