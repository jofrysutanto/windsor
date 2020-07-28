<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactGoogleMap
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
            'center_lat',
            'center_lng',
            'zoom',
            'height',
        ]);
        return $array;
    }
}
