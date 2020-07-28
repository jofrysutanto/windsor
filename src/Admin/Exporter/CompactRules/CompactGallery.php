<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactGallery extends CompactImage
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
            'min',
            'max',
        ]);
        return $array;
    }
}
