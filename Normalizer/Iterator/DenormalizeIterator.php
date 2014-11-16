<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Normalizer\Iterator;

use Bcn\Component\Serializer\Normalizer\NormalizerInterface;

class DenormalizeIterator implements \Iterator
{
    /** @var array */
    protected $iterator = array();

    /** @var NormalizerInterface */
    protected $normalizer;

    /** @var boolean */
    protected $valid;

    /**
     * @param array|\Traversable  $iterator
     * @param NormalizerInterface $normalizer
     */
    public function __construct($iterator, NormalizerInterface $normalizer)
    {
        $this->iterator   = $iterator;
        $this->normalizer = $normalizer;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->normalizer->denormalize(current($this->iterator));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->valid = next($this->iterator);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->iterator);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *                 Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->valid = reset($this->iterator);
    }
}
