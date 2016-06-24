<?php

namespace Statamic\View;

use Exception;
use Statamic\API\Str;
use Statamic\Extend\Management\Loader;
use Statamic\Exceptions\ModifierException;

class Modify
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $context = [];

    /**
     * @var \Statamic\Extend\Management\Loader
     */
    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Specify a value to start the modification chain
     *
     * @param mixed $value
     * @return \Statamic\View\Modify
     */
    public static function value($value)
    {
        $instance = app(self::class);

        $instance->value = $value;

        return $instance;
    }

    /**
     * Set the context
     *
     * @param array $context
     * @return $this
     */
    public function context($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Convert the value to a string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
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
        $this->value = $this->modify($method, array_get($args, 0));

        return $this;
    }

    /**
     * Modify a value
     *
     * @param string $modifier
     * @param array  $params
     * @return mixed
     * @throws \Statamic\Exceptions\ModifierException
     */
    public function modify($modifier, $params = [])
    {
        // Templates will use snake_case to specify modifiers, so we'll
        // convert them to the correct PSR-2 modifier method name.
        $modifier = Str::camel($modifier);

        try {
            // We keep all the native bundled modifiers in one big juicy class
            // rather than a million separate files. We'll check there first.
            if ($value = $this->modifyNatively($modifier, $params)) {
                return $value;
            }

            // Finally we'll attempt to load a modifier in a regular addon location.
            return $this->modifyThirdParty($modifier, $params);

        } catch (ModifierException $e) {
            $e->setModifier($modifier);
            throw $e;

        } catch (Exception $e) {
            $e = new ModifierException($e->getMessage());
            $e->setModifier($modifier);

            throw $e;
        }
    }

    /**
     * Attempt to modify using the native modifiers
     *
     * @param string $modifier
     * @param array $params
     * @return mixed
     */
    protected function modifyNatively($modifier, $params)
    {
        $base_modifiers = app('Statamic\View\BaseModifiers');

        $method = $this->resolveAlias($modifier);

        if (! method_exists($base_modifiers, $method)) {
            return false;
        }

        return $base_modifiers->$method($this->value, $params, $this->context);
    }

    /**
     * Modify using third party addons
     *
     * @param string $modifier
     * @param array  $params
     * @return mixed
     * @throws \Exception
     */
    protected function modifyThirdParty($modifier, $params)
    {
        $class = $this->loader->loadModifier($modifier);

        if (! method_exists($class, 'index')) {
            throw new Exception("Modifier [$modifier] is missing index method.");
        }

        return $class->index($this->value, $params, $this->context);
    }

    /**
     * Resolve a modifier alias
     *
     * @param string $modifier
     * @return string
     */
    protected function resolveAlias($modifier)
    {
        switch (Str::camel($modifier)) {
            case "+":
                return "add";

            case "-":
                return "subtract";

            case "*":
                return "multiply";

            case "/":
                return "divide";

            case "%":
                return "mod";

            case "^":
                return "exponent";

            case "dd":
                return "dump";

            case "ago":
            case "until":
            case "since":
                return "relative";

            case "specialchars":
            case "htmlspecialchars":
                return "sanitize";

            case "striptags":
                return "stripTags";

            case "join":
            case "implode":
            case "list":
                return "joinplode";

            case "json":
                return "toJson";

            case "email":
                return "obfuscateEmail";

            case "l10n":
                return "formatLocalized";

            case "85":
                return "slackEasterEgg";

            case "tz":
                return "timezone";

            default:
                return $modifier;
        }
    }
}