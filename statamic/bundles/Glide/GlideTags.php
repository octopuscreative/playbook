<?php

namespace Statamic\Addons\Glide;

use Statamic\API\Config;
use Statamic\API\Image;
use Statamic\Extend\Tags;
use League\Glide\Urls\UrlBuilderFactory;

class GlideTags extends Tags
{
    /**
     * Maps to {{ glide:[field] }}
     *
     * Where `field` is the variable containing the image ID
     *
     * @param  $method
     * @param  $args
     * @return string
     */
    public function __call($method, $args)
    {
        $tag = explode(':', $this->tag, 2)[1];

        $id = array_get($this->context, $tag);

        return $this->glide($id);
    }

    /**
     * Maps to {{ glide }}
     *
     * Alternate syntax, where you pass the ID directly as a parameter
     *
     * @return string
     */
    public function index()
    {
        $id = $this->get(['src', 'id']);

        return $this->glide($id);
    }

    /**
     * The URL generation
     *
     * @param  string $id ID of the image
     * @return string
     */
    private function glide($id)
    {
        $builder = Image::manipulate($id);

        foreach ($this->parameters as $param => $value) {
            if ($param === 'src') {
                continue;
            }

            $builder->$param($value);
        }

        return $builder->build();
    }
}
