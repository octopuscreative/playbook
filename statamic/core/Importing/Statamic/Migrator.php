<?php

namespace Statamic\Importing\Statamic;

use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\API\File;
use Statamic\API\Page;
use Statamic\API\Path;
use Statamic\API\YAML;
use Statamic\API\Entry;
use Statamic\API\Taxonomy;
use Statamic\API\TaxonomyTerm;

class Migrator
{
    /**
     * The migration array
     *
     * @var array
     */
    private $migration;

    /**
     * Taxonomies
     *
     * @var array
     */
    private $taxonomies;

    /**
     * Create a new Migrator
     *
     * @param array $migration
     */
    public function __construct($migration)
    {
        $this->migration = $this->prepareMigration($migration);
    }

    /**
     * Perform the migration
     *
     * @return void
     */
    public function migrate()
    {
        $this->createTaxonomies();
        $this->createCollections();
        $this->createPages();
    }

    /**
     * Get the migration
     *
     * @return array
     */
    private function prepareMigration($migration)
    {
        $migration['pages'] = $this->sortDeepest($migration['pages']);

        $migration['taxonomies'] = array_get($migration, 'taxonomies', []);

        return $migration;
    }

    /**
     * Sort an array by folder depth (amount of slashes)
     *
     * @param  array $arr An array with paths for keys
     * @return array      The sorted array
     */
    private function sortDeepest($arr)
    {
        uksort($arr, function ($a, $b) {
            return (substr_count($a, '/') >= substr_count($b, '/')) ? 1 : -1;
        });

        // Move homepage to top
        if (isset($arr['/'])) {
            $arr = ['/' => $arr['/']] + $arr;
        }

        return $arr;
    }

    /**
     * Create taxonomies and their terms
     *
     * @return void
     */
    private function createTaxonomies()
    {
        foreach ($this->migration['taxonomies'] as $taxonomy_name => $terms) {
            // Create the taxonomy
            $taxonomy = Taxonomy::create($taxonomy_name);
            $taxonomy->data([
                'title' => Str::title($taxonomy_name)
            ]);
            $taxonomy->save();

            // Create the terms
            foreach ($terms as $slug) {
                $term = TaxonomyTerm::create($slug)->taxonomy($taxonomy_name)->get();
                $term->ensureId();
                $term->save();

                // Add the term to a temporary cache so we can replace slugs with IDs in entries later
                $this->taxonomies[$taxonomy_name][$slug] = $term->id();
            }
        }
    }

    /**
     * Create the collections
     *
     * @return void
     */
    private function createCollections()
    {
        foreach ($this->migration['collections'] as $name => $entries) {
            // In v1, there's no real concept of collections, and you could have entries within
            // any page folder. In v2, there are only root level collections. We'll strip out
            // the slashes and replace them with dashes to make them root level folders.
            $name = str_replace('/', '-', $name);

            $this->createCollection($name, $entries);
            $this->createEntries($name, $entries);
        }
    }

    /**
     * Create a collection folder.yaml
     *
     * @param  string $collection
     * @param  array  $entries
     * @return void
     */
    private function createCollection($collection, $entries)
    {
        $entry = reset($entries);

        $order = $entry['order'];
        if (is_string($order)) {
            $type = 'date';
        } elseif (is_int($order)) {
            $type = 'number';
        } else {
            $type = 'alphabetical';
        }

        $arr = ['order' => $type];

        $path = 'collections/' . $collection . '/folder.yaml';

        File::disk('content')->put($path, YAML::dump($arr));
    }

    /**
     * Create the entries in a collection
     *
     * @param  string $collection
     * @param  array  $entries
     * @return void
     */
    private function createEntries($collection, $entries)
    {
        foreach ($entries as $url => $data) {
            $slug = URL::slug($url);

            if ($this->hasTaxonomies()) {
                $data['data'] = $this->replaceTaxonomies($data['data']);
            }

            $entry = Entry::create($slug)->collection($collection)->with($data['data']);

            if ($data['order']) {
                $entry->order($data['order']);
            }

            $entry = $entry->get();

            $entry->ensureId();

            $entry->save();
        }
    }

    /**
     * Are there taxonomies in the migration?
     *
     * @return bool
     */
    private function hasTaxonomies()
    {
        return ! empty($this->migration['taxonomies']);
    }

    /**
     * Replace slugs in taxonomy fields with their IDs
     *
     * @param  array $data  The array of data to modify
     * @return array        The modified array
     */
    private function replaceTaxonomies($data)
    {
        foreach ($data as $field_name => &$value) {
            if (! $this->isTaxonomyField($field_name)) {
                continue;
            }

            // At the moment, assuming the taxonomy fields are arrays.
            // @todo Handle strings
            foreach ($value as $i => $slug) {
                // Replace the slug with the ID. If it's not found for whatever reason,
                // we'll just leave the slug as-is.
                $value[$i] = array_get($this->taxonomies[$field_name], $slug, $slug);
            }
        }

        return $data;
    }

    /**
     * Is a given $key a taxonomy field name?
     *
     * @param  string  $key
     * @return boolean
     */
    private function isTaxonomyField($key)
    {
        return in_array($key, array_keys($this->taxonomies));
    }

    /**
     * Create pages
     *
     * @return void
     */
    private function createPages()
    {
        // As there may be instances where we need to get the parent pages before their children
        // can be created, we'll create a temporary cache of pages, then write them all at once.
        $pages = [];
        foreach ($this->migration['pages'] as $url => $data) {
            $page = Page::create($url)->with($data['data']);

            if ($order = array_get($data, 'order', $url)) {
                $page->order($order);
            }

            // Getting a page will fail when it's a child of a newly created parent, since it looks
            // in the Stache for the parent's file path. The parent won't exist in the Stache yet,
            // however it will exist in this method's temporary cache, so we'll pass that along.
            try {
                $page = $page->get();
            } catch (\Exception $e) {
                continue;
                $parent = URL::parent($url);
                $parent_path = Path::directory($pages[$parent]->path());
                $page->parentPath($parent_path);
                $page = $page->get();
            }

            $page->ensureId();

            $pages[$url] = $page;
        }

        // Write all the pages to file
        foreach ($pages as $url => $page) {
            $page->save();
        }
    }
}
