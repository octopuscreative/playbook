<?php

namespace Statamic\Http\Controllers;

use Statamic\API\Asset;
use Statamic\API\Assets;
use Statamic\API\Helper;
use Statamic\API\Stache;
use Statamic\Assets\AssetCollection;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Controller for the snippets area
 */
class AssetsController extends CpController
{
    /**
     * The main assets routes, which either browses the first
     * container or redirects to the container listing.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $this->access('assets:*:edit');

        $containers = Assets::getContainers();

        if (count($containers) === 1) {
            return redirect()->route('assets.browse', reset($containers)->uuid());
        }

        return redirect()->route('assets.containers');
    }

    /**
     * List the contents of a particular folder
     *
     * @param string $container  UUID of the container
     * @param string $folder     Path of the folder
     * @return \Illuminate\View\View
     */
    public function browse($container, $folder = '/')
    {
        $this->access('assets:'.$container.':edit');

        $title = translate('cp.browsing_assets');

        $container = Assets::getContainer($container);

        return view('assets.browse', compact('title', 'container', 'folder'));
    }

    public function json()
    {
        $container = Assets::getContainer($this->request->input('container'));

        $folder = $container->folder($this->request->input('folder'));

        $assets = $folder->assets();
        foreach ($assets as &$asset) {
            if ($asset->isImage()) {
                $asset->set('thumbnail', $asset->manipulate()->square(200)->fit('crop_focal')->build());
                $asset->set('toenail', $asset->manipulate()->width(1000)->fit('crop_focal')->build());
            }
        }

        $assets = $assets->toArray();

        $folders = [];
        foreach ($folder->folders() as $f) {
            $folders[] = [
                'path' => $f->path(),
                'title' => $f->title()
            ];
        }

        return [
            'container' => $container->uuid(),
            'folder' => $folder->toArray(),
            'assets' => $assets,
            'folders' => $folders
        ];
    }

    public function get()
    {
        $assets = new AssetCollection;

        foreach ($this->request->input('uuids') as $uuid) {
            $asset = Asset::uuidRaw($uuid);

            if ($asset->isImage()) {
                $asset->set('thumbnail', $asset->manipulate()->square(200)->fit('crop_focal')->build());
                $asset->set('toenail', $asset->manipulate()->width(1000)->fit('crop_focal')->build());
            }

            $assets->put($uuid, $asset);
        }

        return $assets;
    }

    public function create($container, $folder)
    {
        $this->authorize('assets:'.$container.':manage');

        $container = Assets::getContainer($container);

        $folder = $container->folder($folder);

        $title = 'Create asset';

        return view('assets.create', compact('title', 'container', 'folder'));
    }

    public function store()
    {
        if (! $this->request->hasFile('file')) {
            return response()->json('No file specified.', 400);
        }

        $asset = Asset::create()
                      ->container($this->request->input('container'))
                      ->folder($this->request->input('folder'))
                      ->get();

        try {
            $asset->upload($this->request->file('file'));
        } catch (FileException $e) {
            $error = 'There was a problem uploading the file: ' . $e->getMessage();
            return response()->json($error, 400);
        }

        $asset->save();

        Stache::update();

        if ($asset->isImage()) {
            $asset->set('thumbnail', $asset->manipulate()->square(200)->fit('crop_focal')->build());
            $asset->set('toenail', $asset->manipulate()->width(1000)->fit('crop_focal')->build());
        }

        return response()->json([
            'success' => true,
            'asset' => $asset->toArray()
        ]);
    }

    public function edit($uuid)
    {
        $asset = Asset::uuidRaw($uuid);

        $this->authorize('assets:'.Asset::uuidRaw($uuid)->container()->uuid().':edit');

        $fields = $this->populateWithBlanks($asset);

        $asset->set('thumbnail', $asset->manipulate()->square(200)->fit('crop_focal')->build());

        return ['asset' => $asset->toArray(), 'fields' => $fields];
    }

    public function update($uuid)
    {
        /** @var \Statamic\Assets\File\Asset $asset */
        $asset = Asset::uuidRaw($uuid);

        $this->authorize('assets:'.$asset->container()->uuid().':edit');

        $fields = $this->processFields($asset, $this->request->all());

        $asset->data($fields);

        $asset->save();

        return ['success' => true, 'message' => 'Asset updated', 'asset' => $asset->toArray()];
    }

    private function populateWithBlanks($arg)
    {
        // Get a fieldset and data
        $fieldset = $arg->fieldset();
        $data = $arg->processedData();

        // Get the fieldtypes
        $fieldtypes = collect($fieldset->fieldtypes())->keyBy(function($ft) {
            return $ft->getName();
        });

        // Build up the blanks
        $blanks = [];
        foreach ($fieldset->fields() as $name => $config) {
            if (! $default = array_get($config, 'default')) {
                $default = $fieldtypes->get($name)->blank();
            }

            $blanks[$name] = $default;
            if ($fieldtype = $fieldtypes->get($name)) {
                $blanks[$name] = $fieldtype->preProcess($default);
            }
        }

        return array_merge($blanks, $data);
    }

    protected function processFields($asset, $fields)
    {
        foreach ($asset->fieldset()->fieldtypes() as $field) {
            if (! in_array($field->getName(), array_keys($fields))) {
                continue;
            }

            $fields[$field->getName()] = $field->process($fields[$field->getName()]);
        }

        // Get rid of null fields
        $fields = array_filter($fields);

        return $fields;
    }

    public function delete()
    {
        $ids = Helper::ensureArray($this->request->input('ids'));

        foreach ($ids as $id) {
            $this->authorize('assets:'.Asset::uuidRaw($id)->container()->uuid().':delete');
            Asset::uuidRaw($id)->delete();
        }

        return ['success' => true];
    }
}
