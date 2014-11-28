<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Format\Parser\Handler;

use Bcn\Component\Serializer\Format\Parser\Handler\Context\ArrayContext;
use Bcn\Component\Serializer\Format\Parser\Handler\ContextHandler;
use Bcn\Component\Serializer\Tests\TestCase;

class ContextHandlerTest extends TestCase
{
    public function testHandle()
    {
        $context = new ArrayContext();
        $handler = new ContextHandler($context);

        $handler->start('root', array());
        $handler->append("\n    ");
        $handler->start('first', array());
        $handler->append('on');
        $handler->append('e');
        $handler->end('first');
        $handler->start('second', array());
        $handler->append('two');
        $handler->end('second');
        $handler->start('empty', array());
        $handler->end('empty');
        $handler->append("\n    ");
        $handler->end('root');

        $data = array('root' => array('first' => 'one', 'second' => 'two', 'empty' => null));
        $this->assertEquals($data, $context->fetch());
    }

    public function testOutOfScopeException()
    {
        $this->setExpectedException('Exception');

        $handler = new ContextHandler(new ArrayContext());
        $handler->end('root');
    }

    public function testEmptyContextException()
    {
        $this->setExpectedException('Exception');

        $context = $this->getMock('Bcn\Component\Serializer\Format\Parser\Handler\Context\ContextInterface');
        $context->expects($this->once())
            ->method('start')
            ->will($this->returnValue(null));

        $handler = new ContextHandler($context);
        $handler->start('root', array());
    }

    public function testWrongContextException()
    {
        $this->setExpectedException('Exception');

        $context = $this->getMock('Bcn\Component\Serializer\Format\Parser\Handler\Context\ContextInterface');
        $context->expects($this->once())
            ->method('start')
            ->will($this->returnValue(new \stdClass()));

        $handler = new ContextHandler($context);
        $handler->start('root', array());
    }
}
