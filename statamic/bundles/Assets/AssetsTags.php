<?php

namespace Statamic\Addons\Assets;

use Statamic\API\Asset;
use Statamic\API\Assets;
use Statamic\API\Helper;
use Statamic\Extend\Tags;

class AssetsTags extends Tags
{
    /**
     * Iterate over multiple Assets' data from a value
     *
     * Usage:
     * {{ asset:[variable] }}
     *   {{ url }}, etc
     * {{ /asset:[variable] }}
     *
     * @param $method
     * @param $arguments
     * @return string
     */
    public function __call($method, $arguments)
    {
        $value = array_get($this->context, explode(':', $this->tag)[1]);

        return $this->assets($value);
    }
    
    /**
     * Iterate over all assets in a container and optionally by folder
     *
     * Usage:
     * {{ assets path="assets" }}
     *   {{ url }}, etc
     * {{ /assets }}
     * 
     * @return string
     */
    public function index()
    {
        $id = $this->get(['container', 'id']);
        $path = $this->get('path');
        
        if (!$id && !$path) {
            \Log::debug('No asset container ID or path was specified.');
            return;
        }
        
        // Get the assets (container) by either ID or path.
        $assets = ($id) ? Assets::getContainer($id) : Assets::getContainerByPath($path);

        // Optionally target a folder
        if ($folder = $this->get('folder')) {
            $assets = $assets->folder($folder);
        }

        $assets = $assets->assets()->toArray();

        return $this->parseLoop($assets);
    }

    /**
     * Perform the asset lookups
     *
     * @param string|array $ids  One ID, or array of IDs.
     * @return string
     */
    protected function assets($ids)
    {
        if (! $ids) {
            return;
        }

        $ids = Helper::ensureArray($ids);

        $assets = [];

        foreach ($ids as $id) {
            if ($asset = Asset::uuidRaw($id)) {
                $assets[] = $asset->toArray();
            }
        }

        return $this->parseLoop($assets);
    }
}
