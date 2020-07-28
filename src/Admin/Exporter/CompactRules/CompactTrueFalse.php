<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactTrueFalse
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
            'ui_on_text',
            'ui_off_text',
            'message',
        ]);
        $array = $this->unsetIfZero($array, [
            'ui',
        ]);
        return $array;
    }
}
