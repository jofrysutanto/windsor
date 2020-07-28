<?php
namespace Windsor\Admin\Exporter;

use Tightenco\Collect\Support\Collection;

class MutableFieldCollection extends Collection
{

    /**
     * Ensure conversion of attributes to associate array.
     * Optionally receive callback as fallback when given identifier is empty
     *
     * @param string $key
     * @param \Closure $whenMissing
     * @return self
     */
    public function associateBy($key, \Closure $whenMissing = null)
    {
        return $this
            ->keyBy(function ($item) use ($key, $whenMissing) {
                if (isset($item[$key]) && !empty($item[$key])) {
                    return $item[$key];
                }
                if ($whenMissing) {
                    return $whenMissing($item);
                }
                return uniqid($key . '_');
            })
            ->map(function ($item) use ($key) {
                unset($item[$key]);
                return $item;
            });
    }

    /**
     * Transform items in collection into mutable
     *
     * @param \Closure $callback
     * @return self
     */
    public function transformAsMutable($callback)
    {
        $this->transform(function ($item) use ($callback) {
            return call_user_func_array($callback, [new MutableField($item)]);
        });
        return $this;
    }
}
