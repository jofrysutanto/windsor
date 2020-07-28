<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactOEmbed
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
            'width',
            'height',
        ]);
        return $array;
    }
}
