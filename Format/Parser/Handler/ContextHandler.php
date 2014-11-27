<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Format\Parser\Handler;

use Bcn\Component\Serializer\Format\Parser\Handler\Context\ContextInterface;

class ContextHandler implements HandlerInterface
{
    /** @var ContextInterface[] */
    protected $contexts = array();

    /** @var ContextInterface */
    protected $current;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->current    = $context;
        $this->contexts[] = $context;
    }

    /**
     * @param  string     $name
     * @param  array      $attributes
     * @throws \Exception
     */
    public function start($name, array $attributes = array())
    {
        $context = $this->current->start($name, $attributes);

        if ($context === null) {
            throw new \Exception("Context is empty");
        }

        if (!$context instanceof ContextInterface) {
            throw new \Exception("Context should implement ContextInterface");
        }

        $this->contexts[] = $this->current;
        $this->current    = $context;

        $this->current->reset();
    }

    /**
     * @param string $data
     */
    public function append($data)
    {
        $this->current->append($data);
    }

    /**
     * @param  string     $name
     * @throws \Exception
     */
    public function end($name)
    {
        $child = $this->current;
        $this->current = array_pop($this->contexts);

        if (!$this->current) {
            throw new \Exception("Out of Context");
        }

        $this->current->end($name, $child->fetch());
    }
}
