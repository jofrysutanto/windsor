<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactClone
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
            'prefix_label',
            'prefix_name',
        ]);
        $array = $this->formatBoolean($array, [
            'prefix_label',
            'prefix_name',
        ]);
        return $array;
    }
}
