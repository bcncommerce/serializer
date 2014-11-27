<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Tests\Document;
use Bcn\Component\Serializer\Type\AbstractType;
use Bcn\Component\Serializer\Definition\Builder;

class DocumentType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $builder
            ->name('document')
            ->factory(function () { return new Document(); })
            ->node('name', 'text')
                ->property('name')
                ->end()
            ->node('description', 'text')
                ->property('description')
                ->end()
            ->node('rank', 'number')
                ->property('rank')
                ->end()
            ->node('rating', 'number', array('decimals' => 2))
                ->property('rating')
                ->end();

        $this->addParentNode($builder);
    }

    /**
     * @param Builder $builder
     */
    protected function addParentNode(Builder $builder)
    {
        $builder
            ->node('parent')
                ->property('parent')
                ->factory(function () { return new Document(); })
                ->node('name', 'text')
                    ->property('name')
                    ->end()
                ->node('description', 'text')
                    ->property('description')
                    ->end()
                ->node('rank', 'number')
                    ->property('rank')
                    ->end()
                ->node('rating', 'number', array('decimals' => 2))
                    ->property('rating')
                    ->end();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document';
    }
}
