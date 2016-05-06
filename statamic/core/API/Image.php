<?php

namespace Statamic\API;

class Image
{
    /**
     * Get a URL builder instance to continue chaining, or a URL right away if provided with params.
     *
     * @param null|string $uuid
     * @param null|array  $params
     * @return string|\Statamic\Assets\Manipulation\Image\GlideUrlBuilder
     */
    public static function manipulate($uuid = null, $params = null)
    {
        /** @var \Statamic\Assets\Manipulation\Image\GlideUrlBuilder $builder */
        $builder = app('Statamic\Contracts\Assets\Manipulation\UrlBuilder')->uuid($uuid);

        if ($params) {
            return $builder->params($params)->build();
        }

        return $builder;
    }
}
