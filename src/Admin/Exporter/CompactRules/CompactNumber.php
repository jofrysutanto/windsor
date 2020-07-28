<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactNumber
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
            'step',
        ]);
        return $array;
    }
}
