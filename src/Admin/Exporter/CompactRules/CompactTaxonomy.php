<?php
namespace Windsor\Admin\Exporter\CompactRules;

class CompactTaxonomy
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
            'save_terms',
            'load_terms',
            'multiple',
            'allow_null',
        ]);
        $array = $this->unsetIfOne($array, [
            'add_term',
        ]);
        $array = $this->formatBoolean($array, [
            'save_terms',
            'load_terms',
            'multiple',
            'allow_null',
            'add_term',
        ]);
        return $array;
    }
}
