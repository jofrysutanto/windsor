<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactLayout
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
            'min',
            'max',
        ]);
        $array = $this->unsetIfZero($array, [
            'min',
            'max',
        ]);
        return $array;
    }
}
