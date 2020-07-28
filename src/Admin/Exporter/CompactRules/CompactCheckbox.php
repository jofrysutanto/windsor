<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactCheckbox
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
            'allow_custom',
            'toggle',
            'save_custom',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_custom',
            'toggle',
            'save_custom',
        ]);
        $array = $this->unsetIfVal($array, [
            'layout' => 'vertical',
        ]);
        return $array;
    }
}
