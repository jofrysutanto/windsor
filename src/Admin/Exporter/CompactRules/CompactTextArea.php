<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactTextArea
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
            'rows',
            'new_lines',
        ]);
        return $array;
    }
}
