<?php
namespace Windsor\Capsule\Utilities;

use Tightenco\Collect\Support\Arr;

class PrefixConditionalLogic
{
    public function run($acf, $prefixOrCallable = null)
    {
        $conditions = Arr::get($acf, 'conditional_logic', []);
        if (count($conditions) <= 0) {
            return $acf;
        }

        foreach ($conditions as $and => $andContent) {
            foreach ($andContent as $or => $value) {
                $keyReference = Arr::get($value, 'field', '');
                $path = sprintf('conditional_logic.%s.%s.field', $and, $or);

                if (is_string($prefixOrCallable)) {
                    $prefixedKey = $prefixOrCallable . $keyReference;
                } elseif (is_callable($prefixOrCallable)) {
                    $prefixedKey = call_user_func($prefixOrCallable, $keyReference);
                } else {
                    throw new \Exception("Unable to prefix given key: $keyReference");
                }

                Arr::set($acf, $path, $prefixedKey);
            }
        }

        return $acf;
    }
}
