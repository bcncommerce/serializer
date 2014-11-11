<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests;

class Document
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var Document */
    protected $parent;

    /** @var array */
    protected $links = array();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Document
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param  Document $parent
     * @return $this
     */
    public function setParent(Document $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }
}
