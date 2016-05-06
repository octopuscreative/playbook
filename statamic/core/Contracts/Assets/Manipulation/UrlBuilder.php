<?php

namespace Statamic\Contracts\Assets\Manipulation;

interface UrlBuilder
{
    /**
     * Set the UUID of the asset
     *
     * @param string $uuid
     * @return mixed
     */
    public function uuid($uuid);

    /**
     * Return the complete URL
     *
     * @return string
     */
    public function build();
}
