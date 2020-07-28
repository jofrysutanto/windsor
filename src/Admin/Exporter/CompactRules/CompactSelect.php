<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactSelect
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
            'multiple',
            'ui',
            'ajax',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_null',
            'multiple',
            'ui',
            'ajax',
        ]);
        return $array;
    }
}
