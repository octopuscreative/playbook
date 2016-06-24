<?php

namespace Statamic\Addons\Suggest\Modes;

use Statamic\API\Entries;

class CollectionMode extends AbstractMode
{
    public function suggestions()
    {
        $suggestions = [];

        $collection = $this->request->input('collection');

        $entries = Entries::getFromCollection($collection);

        $entries = $entries->multisort($this->request->input('sort', 'title:asc'));

        foreach ($entries as $entry) {
            $suggestions[] = [
                'value' => $entry->id(),
                'text'  => $this->label($entry, 'title')
            ];
        }

        return $suggestions;
    }
}
