<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactUser
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
            'role',
        ]);
        $array = $this->unsetIfZero($array, [
            'multiple',
            'allow_null',
        ]);
        return $array;
    }
}
