<?php
namespace Windsor\Rules\Utilities;

use Tightenco\Collect\Support\Arr;

class TransformConditionalLogic
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
                $prefixedKey = $this->getPrefixedKey(
                    $keyReference,
                    $prefixOrCallable
                );
                if (!$prefixedKey) {
                    throw new \Exception("Unable to prefix given key: $keyReference");
                }
                Arr::set(
                    $acf,
                    sprintf('conditional_logic.%s.%s.field', $and, $or),
                    $prefixedKey
                );
                Arr::set(
                    $acf,
                    sprintf('conditional_logic.%s.%s.value', $and, $or),
                    $this->fixValue(Arr::get($value, 'value'))
                );
            }
        }

        return $acf;
    }

    protected function getPrefixedKey($keyReference, $prefixOrCallable)
    {
        if (is_string($prefixOrCallable)) {
            return $prefixOrCallable . $keyReference;
        } elseif (is_callable($prefixOrCallable)) {
            return call_user_func($prefixOrCallable, $keyReference);
        } else {
            return null;
        }
    }

    /**
     * Adjust values to ensure conditional logic is running correctly
     *
     * @param mixed $val
     * @return mixed
     */
    protected function fixValue($val)
    {
        if (is_bool($val)) {
            return $val === true ? 1 : 0;
        }
        return $val;
    }
}
