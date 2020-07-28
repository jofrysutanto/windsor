<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactTab
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
            'endpoint',
        ]);
        return $array;
    }
}
