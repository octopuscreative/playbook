<?php

namespace Statamic\Data\Entries\File;

use Statamic\API\File;
use Statamic\API\YAML;
use Statamic\API\Config;
use Statamic\API\Folder;
use Statamic\API\Entries;
use Statamic\API\Fieldset;
use Statamic\Data\File\DataFolder;
use Statamic\Contracts\Data\Entries\CollectionFolder as CollectionFolderContract;

class CollectionFolder extends DataFolder implements CollectionFolderContract
{
    /**
     * @var \Statamic\Data\EntryCollection
     */
    protected $entries;

    /**
     * @var \Carbon\Carbon
     */
    protected $last_modified;

    /**
     * @var string|null
     */
    protected $route;

    /**
     * @var string|null
     */
    protected $original_route;

    /**
     * @return int
     */
    public function count()
    {
        return $this->entries()->count();
    }

    /**
     * @return \Statamic\Data\EntryCollection
     */
    public function entries()
    {
        if ($this->entries) {
            return $this->entries;
        }

        if (! $entries = Entries::getFromCollection($this->path())) {
            $entries = collect_entries();
        }

        switch ($this->order()) {
            case 'numeric':
                $entries = $entries->multisort('order:asc');
                break;
            case 'date':
                $entries = $entries->multisort('date:desc');
                break;
            default:
                $entries = $entries->multisort('title:asc');
        }

        return $this->entries = $entries;
    }

    /**
     * @param string                         $key
     * @param \Statamic\Contracts\Data\Entry $entry
     */
    public function addEntry($key, $entry)
    {
        $this->entries->put($key, $entry);
    }

    /**
     * @param string $key
     */
    public function removeEntry($key)
    {
        $this->entries->pull($key);
    }

    /**
     * @return \Carbon\Carbon
     */
    public function lastModified()
    {
        $date = null;

        foreach ($this->entries() as $entry) {
            $modified = $entry->getLastModified();

            if ($date) {
                if ($modified->gt($date)) {
                    $date = $modified;
                }
            } else {
                $date = $modified;
            }
        }

        return $date;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        $path = 'collections/' . $this->path() . '/folder.yaml';

        File::disk('content')->put($path, YAML::dump($this->data()));

        // If the route was modified, update routes.yaml
        if ($this->route && ($this->original_route !== $this->route)) {
            $this->saveRoutes();
        }
    }

    private function saveRoutes()
    {
        $routes = Config::getRoutes();

        array_set($routes, 'collections.'.$this->path(), $this->route());

        $yaml = YAML::dump($routes);

        File::put('site/settings/routes.yaml', $yaml);
    }

    /**
     * Get the collection order
     *
     * @return string
     */
    public function order()
    {
        return $this->get('order', 'alphabetical');
    }

    /**
     * Delete the folder
     *
     * @return mixed
     */
    public function delete()
    {
        $path = 'collections/' . $this->path();

        Folder::disk('content')->delete($path);
    }

    /**
     * Get the URL to edit this in the CP
     *
     * @return string
     */
    public function editUrl()
    {
        return route('collection.edit', $this->path());
    }

    /**
     * Get or set the route definition
     *
     * @return string
     */
    public function route($route = null)
    {
        if (is_null($route)) {
            return $this->route ?: array_get(Config::getRoutes(), 'collections.'.$this->path());
        }

        if (! $this->original_route) {
            $this->original_route = $this->route();
        }

        $this->route = $route;
    }

    /**
     * Get or set the fieldset
     *
     * @param string|null|bool
     * @return Statamic\Contracts\CP\Fieldset
     */
    public function fieldset($fieldset = null)
    {
        if (! is_null($fieldset)) {
            $this->set('fieldset', $fieldset);
        }

        if ($fieldset === false) {
            $this->set('fieldset', null);
        }

        return Fieldset::get([
            $this->get('fieldset'),
            Config::get('theming.default_entry_fieldset'),
            Config::get('theming.default_fieldset')
        ]);
    }
}
