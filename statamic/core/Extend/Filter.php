<?php

namespace Statamic\Extend;

use Illuminate\Support\Collection;

abstract class Filter extends Addon implements FilterInterface
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * @param \Illuminate\Support\Collection $collection
     */
    public function __construct(Collection $collection)
    {
        parent::__construct();

        $this->collection = $collection;
    }
}
