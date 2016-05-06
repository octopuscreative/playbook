<?php

namespace Statamic\Contracts\Importing;

interface Importer
{
    public function name();
    public function title();
    public function instructions();
    public function import($data);
}
