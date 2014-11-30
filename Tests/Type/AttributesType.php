<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Type\AbstractType;
use Bcn\Component\Serializer\Definition\Builder;

class AttributesType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $builder->name('attributes')
            ->prototype()
                ->factory(function () { return array(); })
                ->name('attribute')
                ->node('code',  'text')->property('[code]')->end()
                ->node('value', 'text')->property('[value]')->end()
            ->end()
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'attributes';
    }
}
