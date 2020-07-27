<?php
namespace Windsor\Admin\Exporter\CompactRules;

use Tightenco\Collect\Support\Arr;

class CompactField
{
    use CompactHelper;

    public function run($array)
    {
        $array = $this->unsetIfEmpty($array, [
            'instructions',
            'default_value',
            'placeholder',
            'prepend',
            'append',
            'maxlength',
        ]);
        $array = $this->unsetIfZero($array, [
            'required',
            'conditional_logic',
        ]);
        $array = $this->unsetEmptyWrapper($array);
        $array = $this->expandConditionalLogic($array);
        return $array;
    }

    protected function unsetEmptyWrapper($array)
    {
        if (!Arr::has($array, 'wrapper')) {
            return $array;
        }
        $wrapper = Arr::get($array, 'wrapper');
        if (!is_array($wrapper)) {
            return $array;
        }
        $result = [];
        foreach ($wrapper as $key => $value) {
            if (!empty($value)) {
                $result[$key] = $value;
            }
        }
        if (count($result) <= 0) {
            unset($array['wrapper']);
        } else {
            $array['wrapper'] = $result;
        }
        return $array;
    }

    protected function expandConditionalLogic($array)
    {
        if (!Arr::has($array, 'conditional_logic')) {
            return $array;
        }
        $conditions = Arr::get($array, 'conditional_logic', []);
        if (count($conditions) <= 0) {
            return $array;
        }
        foreach ($conditions as $and => $andContent) {
            foreach ($andContent as $or => $value) {
                Arr::set(
                    $array,
                    sprintf('conditional_logic.%s.%s.field', $and, $or),
                    '~' . Arr::get($value, 'field')
                );
            }
        }
        return $array;
    }
}
