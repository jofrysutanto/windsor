<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactRelationship
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
            'elements',
            'min',
            'max',
        ]);
        return $array;
    }
}
