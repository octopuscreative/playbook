<?php

namespace Statamic\View\Blade;

use Statamic\API\Str;

class Modifier
{
    /**
     * The value in the modifier chain
     *
     * @var mixed
     */
    private $value;

    /**
     * Create a new Modifier class
     *
     * @param [type] $value [description]
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Render the value in a view
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Allow calls to modifiers via method names
     *
     * @param  string $method Modifier name
     * @param  array  $args   Any parameters as arguments
     * @return $this
     */
    public function __call($method, $args)
    {
        $this->value = $this->modify($method, $args);

        return $this;
    }

    /**
     * Modify the value
     *
     * @param  string $modifier    The name of the modifier
     * @param  array  $parameters  Any parameters
     * @return mixed
     */
    private function modify($modifier, $parameters)
    {
        $modifier = Str::camel($modifier); // Map to correct PSR-2 modifier method name

        $context = []; // Blade can't get context.

        try {
            $modifier_method = app('Statamic\View\BaseModifiers')->resolveAlias($modifier);

            if (method_exists(app('Statamic\View\BaseModifiers'), $modifier_method)) {
                // Use the Big List 'O Modifiers
                return app('Statamic\View\BaseModifiers')->$modifier_method($this->value, $parameters, $context);

            } else {
                // load traditional modifier
                $modifier_obj = $this->loader->loadModifier($modifier);

                // ensure method exists
                if (!method_exists($modifier_obj, "index")) {
                    throw new \Exception("Improperly formatted modifier object.");
                }

                // call method
                return $modifier_obj->index($this->value, $parameters, $context);
            }

        } catch (\Exception $e) {
            \Log::notice($e->getMessage());

            return $data;
        }
    }
}
