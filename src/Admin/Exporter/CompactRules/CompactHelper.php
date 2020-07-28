<?php
namespace Windsor\Admin\Exporter\CompactRules;

use Tightenco\Collect\Support\Arr;

trait CompactHelper
{

    /**
     * Unset given keys when the values are empty
     *
     * @param array $array
     * @param array $keys
     * @return array
     */
    protected function unsetIfEmpty($array, $keys)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        foreach ($keys as $key) {
            if (!Arr::has($array, $key)) {
                continue;
            }
            $val = Arr::get($array, $key);
            if (!$val) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Unset given keys when values are zero
     *
     * @param array $array
     * @param array $keys
     * @return array
     */
    protected function unsetIfZero($array, $keys)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        foreach ($keys as $key) {
            if (!Arr::has($array, $key)) {
                continue;
            }
            $val = Arr::get($array, $key);
            if ($val === 0) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Unset given keys when the value match given value
     * ```
     * unsetIfVal(['foo' => 'bar'], ['foo' => 'bar']) => returns [];
     * ```
     *
     * @param array $array
     * @param array $keyValues
     * @return array
     */
    protected function unsetIfVal($array, $keyValues)
    {
        foreach ($keyValues as $key => $value) {
            if (!Arr::has($array, $key)) {
                continue;
            }
            $val = Arr::get($array, $key);
            if ($val === $value) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
