<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactImage
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
            'min_width',
            'min_height',
            'min_size',
            'max_width',
            'max_height',
            'max_size',
            'mime_types',
        ]);

        return $array;
    }
}
