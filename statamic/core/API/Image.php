<?php

namespace Statamic\API;

class Image
{
    /**
     * Get a URL builder instance to continue chaining, or a URL right away if provided with params.
     *
     * @param null|string $item
     * @param null|array  $params
     * @return string|\Statamic\Assets\Manipulation\Image\GlideUrlBuilder
     */
    public static function manipulate($item = null, $params = null)
    {
        /** @var \Statamic\Assets\Manipulation\Image\GlideUrlBuilder $builder */
        $builder = app('Statamic\Contracts\Assets\Manipulation\UrlBuilder');

        if (Str::isUrl($item)) {
            $builder->path($item);
        } else {
            $builder->id($item);
        }

        if ($params) {
            return $builder->params($params)->build();
        }

        return $builder;
    }
}
