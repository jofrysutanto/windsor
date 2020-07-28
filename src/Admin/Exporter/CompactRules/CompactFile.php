<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactFile
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
            'min_size',
            'max_size',
            'mime_types',
        ]);
        return $array;
    }
}
