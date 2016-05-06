<?php

namespace Statamic\Http\Controllers;

use Statamic\API\Asset;
use Statamic\API\Config;
use League\Glide\Server;
use Statamic\API\Stache;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class GlideController extends Controller
{
    private $server;
    private $request;
    private $asset;

    public function __construct(Server $server, Request $request)
    {
        $this->server = $server;
        $this->request = $request;
    }

    public function generate($uuid, $filename = null)
    {
        // If the asset doesn't exist, we'll need to update the stache first.
        if (! $this->asset = Asset::uuidRaw($uuid)) {
            Stache::update();
        }

        // If it still doesn't exist, well then, it really doesn't exist.
        $this->asset = Asset::uuidRaw($uuid);

        // Set the source of the server to the directory where the requested image will be.
        // Then all we have to do is pass in the basename of the file to be manipulated.
        $this->server->setSource($this->asset->disk()->filesystem()->getDriver());
        $this->server->setSourcePathPrefix($this->asset->folder()->path());

        // Set the cache path so files are saved appropriately.
        $this->server->setCachePathPrefix($this->asset->container()->id() . '/' . $this->asset->folder()->path());

        $this->applyDefaultManipulations();

        // If secure images are enabled, we'll validate the signature
        if (Config::get('assets.image_manipulation_secure')) {
            try {
                $verification_path = Config::get('assets.image_manipulation_route') . '/' . $uuid;
                $verification_path .= ($filename) ? '/'.$filename : '';
                SignatureFactory::create(Config::getAppKey())->validateRequest($verification_path, $_GET);
            } catch (SignatureException $e) {
                return response($e->getMessage(), 400);
            }
        }

        $path = $this->server->makeImage($this->asset->basename(), $this->request->all());

        event('glide.generated', [
            cache_path('glide/'.$path),
            $this->request->all()
        ]);

        return $this->server->getResponseFactory()->create($this->server->getCache(), $path);
    }

    private function applyDefaultManipulations()
    {
        $defaults = [];

        // Enable automatic cropping
        if (Config::get('assets.auto_crop')) {
            $defaults['fit'] = 'crop-'.$this->asset->get('focus', '50-50');
        }

        // @todo: Allow user defined defaults and merge them in here.

        $this->server->setDefaults($defaults);
    }
}
