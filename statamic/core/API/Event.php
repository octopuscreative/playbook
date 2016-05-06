<?php

namespace Statamic\API;

class Event
{
    public static function fire($event, $payload = [])
    {
        return event($event, $payload);
    }
}
