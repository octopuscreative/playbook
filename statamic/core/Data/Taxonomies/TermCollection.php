<?php

namespace Statamic\Data\Taxonomies;

use Statamic\Data\Content\ContentCollection;

class TermCollection extends ContentCollection
{
    /**
     * Filter the taxonomy's content collection by a closure
     *
     * @param callable $filter
     * @return \Statamic\Data\Taxonomies\TermCollection
     */
    public function filterContent(callable $filter)
    {
        $collection = new self;

        // Go through each taxonomy and apply the appropriate filter to its collection of content.
        $this->each(function($taxonomy, $name) use ($collection, $filter) {
            /** @var \Statamic\Contracts\Data\Taxonomies\Taxonomy $taxonomy */

            $content = $taxonomy->collection();

            // Perform the requested filter, passing in the ContentCollection as an argument
            $content = call_user_func($filter, $content);

            // Once the filter has been completed, it's possible that a taxonomy may have no content.
            // If that's the case, we'll just leave it off (ie. remove it) from the collection.
            if ($content->count()) {
                $taxonomy->collection($content);
                $collection->put($name, $taxonomy);
            }
        });

        return $collection;
    }

    /**
     * Sort by count
     *
     * @param bool $reverse Sort in reverse order
     * @return static
     */
    public function sortByCount($reverse = false)
    {
        // Swap around the reverse parameter. By default we want to sort in descending order.
        $desc = !$reverse;

        return $this->sortBy(function($taxonomy) {
            return $taxonomy->count();
        }, null, $desc);
    }
}
