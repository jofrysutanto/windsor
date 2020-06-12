<?php
namespace Windsor\Support;

use ArrayAccess;
use Tightenco\Collect\Contracts\Support\Arrayable;

class Config implements Arrayable, ArrayAccess
{

    /**
     * Configuration attributes
     *
     * @var array
     */
    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Initialise configuration values
     *
     * @return $this
     */
    public static function initialise()
    {
        return new static(apply_filters('acf-windsor/config', static::getDefault()));
    }

    /**
     * Replace configuration values
     *
     * @param array $attributes
     * @return \Windsor\Support\Config
     */
    public function setValues($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Retrieve default configuration values
     *
     * @return array
     */
    public static function getDefault()
    {
        $path = __DIR__ .'/../config.php';
        if (!file_exists($path)) {
            throw new \Exception('Missing configuration file, unable to initialise Windsor helper');
        }
        return require_once $path;
    }

    /**
     * Get an attribute from the fluent instance.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return value($default);
    }

    /**
     * Get the attributes from the fluent instance.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Convert the fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the fluent instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}
