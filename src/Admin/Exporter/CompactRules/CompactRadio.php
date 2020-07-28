<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactRadio
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
            'other_choice',
            'save_other_choice',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_null',
            'other_choice',
            'save_other_choice',
        ]);
        $array = $this->unsetIfVal($array, [
            'layout' => 'vertical',
        ]);
        return $array;
    }
}
