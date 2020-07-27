<?php
namespace Windsor\Admin\Exporter\CompactRules;

use Tightenco\Collect\Support\Arr;

trait CompactHelper
{
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
