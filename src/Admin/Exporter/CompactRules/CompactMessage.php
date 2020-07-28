<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactMessage
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
            'esc_html',
        ]);
        $array = $this->formatBoolean($array, [
            'esc_html',
        ]);
        return $array;
    }
}
