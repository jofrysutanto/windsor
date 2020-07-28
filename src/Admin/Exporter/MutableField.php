<?php
namespace Windsor\Admin\Exporter;

use Windsor\Support\Fluent;

class MutableField extends Fluent
{

    /**
     * Shift given key to last, only works for associative array
     *
     * @param string $key
     * @return self
     */
    public function shiftToLast($key)
    {
        $pos = array_search($key, array_keys($this->attributes));
        if ($pos === false) {
            return $this;
        }
        $item = array_splice($this->attributes, $pos, 1);
        $this->attributes = array_merge($this->attributes, $item);
        return $this;
    }

    public function when($condition, $callback)
    {
        if ($condition !== true) {
            return $this;
        }
        call_user_func_array($callback, [$this]);
        return $this;
    }

    public function modifyAsMutable($key, $callback = null)
    {
        if (is_callable($key)) {
            $mutable = new static($this->attributes);
            $this->attributes = call_user_func_array($key, [$mutable])->toArray();
            return $this;
        }
        $value = $this->get($key);
        if (!$value) {
            return $this;
        }
        $this->attributes[$key] = call_user_func_array($callback, [new static($value)])->toArray();
        return $this;
    }

    public function modify($key, $callback = null)
    {
        if (is_callable($key)) {
            $this->attributes = call_user_func_array($key, [$this->attributes]);
            return $this;
        }
        $value = $this->get($key);
        if (!$value) {
            return $this;
        }
        $this->attributes[$key] = call_user_func_array($callback, [$value]);
        return $this;
    }

    /**
     * Run compact rules on given array
     *
     * @param array $rules Instantiable rule class
     * @return self
     */
    public function compactify($rules)
    {
        foreach ($rules as $rule) {
            $this->attributes = (new $rule)->run($this->attributes);
        }
        return $this;
    }

    /**
     * Dump current attributes, for debugging purpose
     *
     * @return self
     */
    public function dump()
    {
        dump($this->attributes);
        return $this;
    }
}
