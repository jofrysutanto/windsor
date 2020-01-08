<?php

namespace AcfYaml\Capsule\Rules;

use AcfYaml\Capsule\FieldGroup;

class HelperRule
{
    /**
     * Process this rule
     *
     * @param FieldGroup  $group
     * @param string $key
     * @param array $acf
     * @return array
     */
    public function process($group, $key, array $acf): array
    {
        if (!$group->isDebugging()) {
            return $acf;
        }
        $instruction = array_get($acf, 'instructions', '');
        $instruction .= $this->getDebugHtml($group, $key);
        array_set($acf, 'instructions', $instruction);
        return $acf;
    }

    /**
     * Get debug HTML helper
     *
     * @param FieldGroup $group
     * @param string $key
     * @return string
     */
    protected function getDebugHtml($group, $key)
    {
        return sprintf("
            <span style='font-size: 11px; display: inline-block;'>
             Key: %s
            </span>
        ", $key);
    }
}
