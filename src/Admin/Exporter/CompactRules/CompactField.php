<?php
namespace Windsor\Admin\Exporter\CompactRules;

use Tightenco\Collect\Support\Arr;

class CompactField
{
    use CompactHelper;

    /**
     * Run this rule
     *
     * @param array $array
     * @return array
     */
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
        return $array;
    }

    /**
     * Unset empty wrapper key with empty values,
     * or completely remove wrapper key if empty
     *
     * @param array $array
     * @return array
     */
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
}
