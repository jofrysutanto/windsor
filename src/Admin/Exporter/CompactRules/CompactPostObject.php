<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactPostObject
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
            'post_type',
            'taxonomy',
        ]);
        $array = $this->unsetIfZero($array, [
            'allow_null',
            'multiple',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_null',
            'multiple',
            'ui',
        ]);
        return $array;
    }
}
