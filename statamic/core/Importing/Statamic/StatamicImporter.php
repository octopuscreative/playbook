<?php

namespace Statamic\Importing\Statamic;

use Statamic\Importing\Importer;

class StatamicImporter extends Importer
{
    public function name()
    {
        return 'statamic';
    }

    public function title()
    {
        return 'Statamic v1';
    }

    public function instructions()
    {
        return "
Download the `exporter` addon from
[http://github.com/statamic/exporter](http://github.com/statamic/exporter) and install
it into your v1 site.

Visit `http://your-v1-site.com/TRIGGER/exporter/export` and you should be greeted by a JSON string.
Copy and paste that into the next step.";

    }

    public function import($data)
    {
        $migrator = new Migrator($data);

        $migrator->migrate();
    }
}
