<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactRichEditor
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
        $array = $this->unsetIfZero($array, [
            'delay',
        ]);
        $array = $this->formatBoolean($array, [
            'media_upload',
        ]);
        return $array;
    }
}
