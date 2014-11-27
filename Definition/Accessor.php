<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Definition;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Accessor
{
    /** @var object|array */
    protected $object;

    /** @var string */
    protected $path;

    /** @var PropertyAccessorInterface */
    protected $accessor;

    /**
     * @param object|array              $object
     * @param string                    $path
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(&$object, $path, PropertyAccessorInterface $accessor = null)
    {
        $this->object   = &$object;
        $this->path     = $path;
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param mixed $value
     */
    public function set($value)
    {
        if ($this->path) {
            $this->accessor->setValue($this->object, $this->path, $value);
        } else {
            $this->object = $value;
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if ($this->path) {
            return $this->accessor->getValue($this->object, $this->path);
        }

        return $this->object;
    }
}
