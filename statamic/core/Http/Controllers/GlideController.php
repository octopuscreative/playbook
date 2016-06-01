<?php

namespace Statamic\Http\Controllers;

use Statamic\API\Asset;
use Statamic\API\Config;
use League\Glide\Server;
use Statamic\API\File;
use Statamic\API\Stache;
use Illuminate\Http\Request;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class GlideController extends Controller
{
    /**
     * @var \League\Glide\Server
     */
    private $server;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var \Statamic\Contracts\Assets\Asset
     */
    private $asset;

    /**
     * @var string
     */
    private $path;

    /**
     * GlideController constructor.
     *
     * @param \League\Glide\Server     $server
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Server $server, Request $request)
    {
        $this->server = $server;
        $this->request = $request;
    }

    /**
     * Generate a manipulated image by a path
     *
     * @param string $path
     * @return mixed
     */
    public function generateByPath($path)
    {
        $this->path = $path;

        $this->server->setCachePathPrefix('default');

        return $this->generate($path);
    }

    /**
     * Generate a manipulated image by an asset ID
     *
     * @param string $id
     * @return mixed
     * @throws \Exception
     */
    public function generateByAsset($id)
    {
        $this->ensureAsset($id);

        // Set the source of the server to the directory where the requested image will be.
        // Then all we have to do is pass in the basename of the file to be manipulated.
        $this->server->setSource($this->asset->disk()->filesystem()->getDriver());
        $this->server->setSourcePathPrefix($this->asset->folder()->path());

        // Set the cache path so files are saved appropriately.
        $this->server->setCachePathPrefix($this->asset->container()->id() . '/' . $this->asset->folder()->path());

        return $this->generate($this->asset->basename());
    }

    /**
     * Generate the image
     *
     * @param string $image The filename of the image
     * @return mixed
     * @throws \Exception
     * @throws \League\Glide\Filesystem\FileNotFoundException
     * @throws \League\Glide\Filesystem\FilesystemException
     */
    private function generate($image)
    {
        $this->applyDefaultManipulations();
        $this->validateSignature();
        $this->validateImage();

        $path = $this->server->makeImage($image, $this->request->all());

        event('glide.generated', [
            cache_path('glide/'.$path),
            $this->request->all()
        ]);

        return $this->server->getResponseFactory()->create($this->server->getCache(), $path);
    }

    /**
     * Apply default Glide manipulations on the image
     *
     * @return void
     */
    private function applyDefaultManipulations()
    {
        $defaults = [];

        // Enable automatic cropping
        if (Config::get('assets.auto_crop') && $this->asset) {
            $defaults['fit'] = 'crop-'.$this->asset->get('focus', '50-50');
        }

        // @todo: Allow user defined defaults and merge them in here.

        $this->server->setDefaults($defaults);
    }

    /**
     * Validate the signature, if applicable
     *
     * @return void
     */
    private function validateSignature()
    {
        // If secure images aren't enabled, don't bother validating the signature.
        if (! Config::get('assets.image_manipulation_secure')) {
            return;
        }

        try {
            SignatureFactory::create(Config::getAppKey())->validateRequest($this->request->path(), $_GET);
        } catch (SignatureException $e) {
            abort(400, $e->getMessage());
        }
    }

    /**
     * Ensure that the image is actually an image
     *
     * @throws \Exception
     */
    private function validateImage()
    {
        if ($this->asset) {
            $path = $this->asset->path();
            $mime = $this->asset->disk()->mimeType($path);
        } else {
            $path = $this->path;
            $mime = File::mimeType(STATAMIC_ROOT.'/'.$this->path);
        }

        if ($mime !== null && strncmp($mime, 'image/', 6) !== 0) {
            throw new \Exception("Image [{$path}] does not actually appear to be an image.");
        }
    }

    /**
     * Make sure that an asset has been fetched
     *
     * @param string $id
     * @throws \Exception
     */
    private function ensureAsset($id)
    {
        // If the asset doesn't exist, we'll need to update the stache first.
        if (! $this->asset = Asset::uuidRaw($id)) {
            Stache::update();
        }

        // If it still doesn't exist, well then, it really doesn't exist.
        $this->asset = Asset::uuidRaw($id);

        if (! $this->asset) {
            throw new \Exception("Asset with ID [$id] doesn't exist.");
        }
    }
}
