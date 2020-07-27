<?php
namespace Windsor\Admin\Exporter\CompactRules;

use Tightenco\Collect\Support\Arr;

class CompactImage
{
    use CompactHelper;

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
