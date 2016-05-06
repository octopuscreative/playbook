<?php

namespace Statamic\Assets\Manipulation\Image;

use Exception;
use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\API\Asset;
use Statamic\API\Config;
use League\Glide\Urls\UrlBuilderFactory;
use Statamic\Contracts\Assets\Manipulation\UrlBuilder;

class GlideUrlBuilder implements UrlBuilder
{
    /**
     * Methods available in Glide's API
     *
     * @var array
     */
    private $api = [
        'or', 'crop', 'w', 'h', 'fit', 'dpr', 'bri', 'con', 'gam', 'sharp', 'blur', 'pixel', 'filt',
        'mark', 'markw', 'markx', 'marky', 'markpad', 'markpos', 'bg', 'border', 'q', 'fm'
    ];

    /**
     * UUID of the asset
     *
     * @var string
     */
    private $uuid;

    /**
     * The vanity filename
     *
     * @var string
     */
    private $filename;

    /**
     * Parameters being built
     *
     * @var array
     */
    private $params = [];

    /**
     * Handle unknown method calls
     *
     * @param string $method
     * @param array $args
     * @return $this
     */
    public function __call($method, $args)
    {
        $this->setParam($method, $args[0]);

        return $this;
    }

    /**
     * Set a parameter
     *
     * @param string $param
     * @param mixed  $value
     * @throws \Exception
     */
    private function setParam($param, $value)
    {
        // Error out when given an unknown parameter.
        if (! in_array($param, $this->api)) {
            throw new Exception("Glide URL parameter [$param] does not exist.");
        }

        $this->params[$param] = $value;
    }

    /**
     * Set the parameters
     *
     * @param array $params
     * @return $this
     */
    public function params(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Set the asset UUID
     *
     * @param string $uuid
     * @return $this
     */
    public function uuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Set the filename
     *
     * @param  string $filename
     * @return $this
     */
    public function filename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function width($value)
    {
        $this->params['w'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function height($value)
    {
        $this->params['h'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function fit($value)
    {
        if ($value == 'crop_focal') {
            $value = 'crop';
            if ($asset = Asset::uuidRaw($this->uuid)) {
                if ($focus = $asset->get('focus')) {
                    $value .= '-' . $focus;
                }
            }
        }

        $this->params['fit'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function crop($value)
    {
        $this->params['crop'] = $value;

        return $this;
    }

    /**
     * @param int $pixels
     * @return $this
     */
    public function square($pixels)
    {
        $this->params['w'] = $pixels;
        $this->params['h'] = $pixels;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function orient($value)
    {
        $this->params['or'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function brightness($value)
    {
        $this->params['bri'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function contrast($value)
    {
        $this->params['con'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function gamma($value)
    {
        $this->params['gam'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function sharpen($value)
    {
        $this->params['sharp'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function blur($value)
    {
        $this->params['blur'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function pixelate($value)
    {
        $this->params['pixel'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function filter($value)
    {
        $this->params['filt'] = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function quality($value)
    {
        $this->params['q'] = $value;

        return $this;
    }

    /**
     * Return the complete URL
     *
     * @return string
     */
    public function build()
    {
        $key = (Config::get('assets.image_manipulation_secure')) ? Config::getAppKey() : null;

        $builder = UrlBuilderFactory::create(Config::get('assets.image_manipulation_route'), $key);

        $path = $this->uuid;

        if ($this->filename) {
            $path .= Str::ensureLeft($this->filename, '/');
        }

        return URL::prependSiteRoot($builder->getUrl($path, $this->params));
    }
}
