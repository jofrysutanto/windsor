<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactPageLink
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
        $array = $this->unsetIfOne($array, [
            'allow_archives',
        ]);
        $array = $this->formatBoolean($array, [
            'allow_null',
            'allow_archives',
            'multiple',
        ]);
        return $array;
    }
}
