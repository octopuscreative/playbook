<?php

namespace Statamic\Data;

use Statamic\Exceptions\MethodNotFoundException;
use Illuminate\Support\Collection as IlluminateCollection;

/**
 * An abstract collection of data types
 */
abstract class DataCollection extends IlluminateCollection
{
    /**
     * Limit a collection
     *
     * @param $limit
     * @return static
     */
    public function limit($limit)
    {
        return $this->take($limit);
    }

    /**
     * Walk over an array of methods and attempt to run each one
     *
     * @param array $actions
     * @return \Statamic\Data\DataCollection
     * @throws \Statamic\Exceptions\MethodNotFoundException
     */
    public function actions($actions)
    {
        $collection = $this;

        foreach ($actions as $method => $arguments) {
            if (! method_exists($this, $method)) {
                throw new MethodNotFoundException("The `$method` method doesn't exist.");
            }

            $collection = call_user_func_array([$collection, $method], (array) $arguments);
        }

        return $collection;
    }

    /**
     * Filter the Collection by condition(s)
     *
     * @param string $conditions
     * @return \Statamic\Data\DataCollection
     */
    public function conditions($conditions)
    {
        $filterer = app('Statamic\Data\Filters\ConditionFilterer');

        return $filterer->filter($this, $conditions);
    }

    /**
     * Add a new key to each item of the collection
     *
     * @param string   $key New key to add
     * @param callable $callable Function to return the new value
     * @return \Statamic\Data\DataCollection
     */
    public function supplement($key, callable $callable)
    {
        if (! is_callable($callable, false)) {
            return $this;
        }

        foreach ($this->items as $i => $item) {
            $this->items[$i]->setSupplement($key, call_user_func($callable, $item));
        }

        return $this;
    }

    /**
     * Get the collection as a plain array
     *
     * @return array
     */
    public function toArray()
    {
        return array_values(parent::toArray());
    }

    /**
     * Get the collection as a plain array using only selected keys
     *
     * @param array $keys
     * @return array
     */
    public function toArrayWith($keys)
    {
        $array = [];

        foreach ($this->items as $i => $item) {
            foreach ($keys as $key) {
                // First try to get the supplemented value
                if (! $data = $item->getSupplement($key)) {
                    // Then try the data / front-matter
                    if (!$data = $item->get($key)) {
                        // Finally try getting a property via its getter method
                        $method = 'get' . ucfirst($key);
                        if (method_exists($item, $method)) {
                            $data = call_user_func([$item, $method]);
                        }
                    }
                }

                $array[$i][$key] = $data;
            }
        }

        return array_values($array);
    }
}
