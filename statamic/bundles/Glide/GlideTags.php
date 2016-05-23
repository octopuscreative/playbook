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
     * Alternate syntax, where you pass the ID or path directly as a parameter or tag pair content
     *
     * @return string
     */
    public function index()
    {
        $item = ($this->content)
            ? $this->parse([])
            : $this->get(['src', 'id', 'path']);

        return $this->glide($item);
    }

    /**
     * The URL generation
     *
     * @param  string $item  Either the ID or path of the image.
     * @return string
     */
    private function glide($item)
    {
        $builder = Image::manipulate($item);

        foreach ($this->parameters as $param => $value) {
            if (in_array($param, ['src', 'path'])) {
                continue;
            }

            $builder->$param($value);
        }

        return $builder->build();
    }
}
