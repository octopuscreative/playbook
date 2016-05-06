<?php

namespace Statamic\Exceptions;

/**
 * Trigger a redirect
 */
class RedirectException extends \Exception
{
    private $url;

    private $status_code;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }
}
