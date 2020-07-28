<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactImage extends CompactFile
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
        $array = parent::run($array);
        $array = $this->unsetIfEmpty($array, [
            'min_width',
            'min_height',
            'max_width',
            'max_height',
            'min_size',
            'max_size',
        ]);
        return $array;
    }
}
