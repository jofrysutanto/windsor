<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactFieldGroup
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
            'description',
            'hide_on_screen',
        ]);
        $array = $this->unsetIfZero($array, [
            'menu_order',
        ]);
        $array = $this->unsetIfVal($array, [
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ]);
        return $array;
    }
}
