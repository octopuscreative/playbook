<?php

namespace Statamic\Http\Controllers;

use Statamic\API\Str;

class ImportController extends CpController
{
    public function index()
    {
        $this->access('importer');

        return view('import.index');
    }

    public function import($name)
    {
        $this->access('importer');

        $importer = $this->importer($name);

        return view('import.import', compact('importer'));
    }

    public function upload($name)
    {
        $this->access('importer');

        $json = json_decode($this->request->input('json'), true);

        $this->importer($name)->import($json);

        return ['success' => true];
    }

    private function importer($name)
    {
        $this->access('importer');

        $studly = Str::studly($name);
        $class = "Statamic\\Importing\\$studly\\{$studly}Importer";

        return new $class;
    }
}
