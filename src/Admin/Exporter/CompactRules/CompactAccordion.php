<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactAccordion
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
            'open',
            'multi_expand',
            'endpoint',
        ]);
        $array = $this->formatBoolean($array, [
            'open',
            'multi_expand',
            'endpoint',
        ]);
        return $array;
    }
}
