<?php

namespace Statamic\Extend;

use Statamic\API\Arr;
use Statamic\API\Parse;
use Statamic\API\Helper;
use Statamic\Data\DataCollection;

/**
 * Template tags
 */
abstract class Tags extends Addon
{
    /**
     * An array of parameters set on this tag
     * @public array
     */
    public $parameters;

    /**
     * The content written between the tags (when a tag pair)
     * @public string
     */
    public $content;

    /**
     * The variable context around which this tag is positioned
     * @public array
     */
    public $context;

    /**
     * The tag that was used (without any parameters), eg. ron:swanson
     * @var string
     */
    public $tag;

    /**
     * Whether to trim the whitespace from the content before parsing
     * @var  bool
     */
    protected $trim = false;

    /**
     * Set the properties
     *
     * @param array  $properties  Properties that to set
     * @return Tags
     */
    public function __construct($properties)
    {
        $this->parameters  = $properties['parameters'];
        $this->content     = $properties['content'];
        $this->context     = $properties['context'];
        $this->tag         = array_get($properties, 'tag');

        parent::__construct();
    }

    /**
     * Retrieves a parameter or config value
     *
     * @param string|array $keys Keys of parameter to return
     * @param null         $default
     * @return mixed
     */
    protected function get($keys, $default = null)
    {
        return Helper::pick(
            $this->getParam($keys, $default),
            $this->getConfig($keys, $default),
            $default
        );
    }

    /**
     * Same as $this->get(), but treats as a boolean
     *
     * @param string|array $keys
     * @param false         $default
     * @return bool
     */
    protected function getBool($keys, $default = false)
    {
        return bool($this->get($keys, $default));
    }

    /**
     * Same as $this->get(), but treats as a float
     *
     * @param string|array $keys
     * @param null         $default
     * @return float
     */
    protected function getFloat($keys, $default = null)
    {
        return (float) $this->get($keys, $default);
    }

    /**
     * Same as $this->get(), but treats as an integer
     *
     * @param string|array $keys
     * @param null         $default
     * @return int
     */
    protected function getInt($keys, $default = null)
    {
        return int($this->get($keys, $default));
    }

    /**
     * Retrieves a parameter
     *
     * @param string|array $keys Keys of parameter to return
     * @param mixed $default  Default value to return if not set
     * @return mixed
     */
    protected function getParam($keys, $default = null)
    {
        if (! is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            if (isset($this->parameters[$key])) {
                return $this->parameters[$key];
            }
        }

        return $default;
    }

    /**
     * Same as $this->getParam(), but treats as a boolean
     *
     * @param string|array $keys
     * @param null         $default
     * @return bool
     */
    protected function getParamBool($keys, $default = null)
    {
        return bool($this->getParam($keys, $default));
    }

    /**
     * Same as $this->getParam(), but treats as an integer
     *
     * @param string|array $keys
     * @param null         $default
     * @return int
     */
    protected function getParamInt($keys, $default = null)
    {
        return int($this->getParam($keys, $default));
    }

    /**
     * Retrieves a parameters and explodes any | delimiters
     *
     * @param string|array $keys
     * @param null         $default
     * @return int
     */
    protected function getList($keys, $default = null)
    {
        $keys = $this->getParam($keys, $default);

        return ($keys) ? explode('|', $keys) : $default;
    }

    /**
     * Trim the content
     *
     * @param   bool    $trim  Whether to trim the content
     * @return  this
     */
    protected function trim($trim = true)
    {
        $this->trim = $trim;

        return $this;
    }

    /**
     * Parse the tag pair contents with scoped variables
     *
     * @param array $data     Data to be parsed into template
     * @param array $context  Contextual variables to also use
     * @return string
     */
    protected function parse($data, $context = [])
    {
        if ($this->trim) {
            $this->content = trim($this->content);
        }

        $context = array_merge($context, $this->context);

        return Parse::template($this->content, $this->addScope($data), $context);
    }

    /**
     * Iterate over the data and parse the tag pair contents for each, with scoped variables
     *
     * @param array|\Statamic\Data\DataCollection $data        Data to iterate over
     * @param bool                                $supplement  Whether to supplement with contextual values
     * @param array                               $context     Contextual variables to also use
     * @return string
     */
    protected function parseLoop($data, $supplement = true, $context = [])
    {
        if ($this->trim) {
            $this->content = trim($this->content);
        }

        $context = array_merge($context, $this->context);

        return Parse::templateLoop($this->content, $this->addScope($data), $supplement, $context);
    }

    /**
     * Add the provided $data to its own scope
     *
     * @param array|\Statamic\Data\DataCollection $data
     * @return mixed
     */
    private function addScope($data)
    {
        if ($scope = $this->getParam('scope')) {
            $data = Arr::addScope($data, $scope);
        }

        if ($data instanceof DataCollection) {
            $data = $data->toArray();
        }

        return $data;
    }
}
