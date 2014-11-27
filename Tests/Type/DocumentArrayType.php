<?php
/**
 * This file is part of the serializer project
 *
 * (c) Sergey Kolodyazhnyy <sergey.kolodyazhnyy@gmail.com>
 *
 */

namespace Bcn\Component\Serializer\Tests\Type;

use Bcn\Component\Serializer\Definition\Builder;
use Bcn\Component\Serializer\Type\AbstractType;

class DocumentArrayType extends AbstractType
{
    /**
     * @param  Builder $builder
     * @param  array   $options
     * @return mixed
     */
    public function build(Builder $builder, array $options = array())
    {
        $builder
            ->name('documents')
            ->prototype('document')
                ->name('document')
            ->end();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document_array';
    }
}
