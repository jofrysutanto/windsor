<?php
namespace Windsor\Capsule;

abstract class AbstractCapsule
{

    /**
     * @var array Current active namespace
     */
    public static $namespace = [];

    /**
     * @var array Valid ACF field according to ACF's standard
     */
    protected $parsed;

    /**
     * Debug mode flag
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     * Create and parse group with given content
     *
     * @param array $content
     * @return self
     */
    abstract public function make($content);

    /**
     * Retrieve parsed and valid ACF array
     *
     * @return array
     */
    public function parsed()
    {
        return $this->parsed;
    }

    /**
     * Group and scope all fields to given namespace.
     * The namespace is used to prefix all fields to ensure their uniqueness.
     *
     * @param string $namespace
     * @param Closure $callback
     * @return self
     */
    public function namespace($namespace, $callback)
    {
        static::$namespace[] = $namespace;
        $callback($this);
        array_pop(static::$namespace);
        return $this;
    }

    /**
     * Generate unique key based on current active namespace
     *
     * @param string $key
     * @return string
     */
    public function makeKey($key)
    {
        if (count(static::$namespace) <= 0) {
            return $key;
        }
        if (starts_with($key, '~')) {
            return ltrim($key, '~');
        }
        $namespace = array_values(array_slice(static::$namespace, -1))[0];
        return $namespace . '_' . $key;
    }

    /**
     * Set debug mode
     *
     * @param boolean $isDebugging
     * @return self
     */
    public function setDebug($isDebugging = true)
    {
        $this->debug = $isDebugging;
        return $this;
    }

    /**
     * Check debug mode
     *
     * @return boolean
     */
    public function isDebugging()
    {
        return $this->debug;
    }
}
