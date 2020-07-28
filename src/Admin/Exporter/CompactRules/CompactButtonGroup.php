<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactButtonGroup
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
        $array = $this->unsetIfZero($array, [
            'allow_null',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_null',
        ]);
        $array = $this->unsetIfVal($array, [
            'layout' => 'horizontal',
        ]);
        return $array;
    }
}
