<?php
namespace Windsor\Rules;

use Windsor\Capsule\FieldGroup;
use Tightenco\Collect\Support\Arr;

class HelperRule
{
    /**
     * Process this rule
     *
     * @param FieldGroup $group
     * @param string $key
     * @param array $acf
     * @return array
     */
    public function process($group, $key, array $acf): array
    {
        if (!$group->isDebugging()) {
            return $acf;
        }
        $instruction = Arr::get($acf, 'instructions', '');
        $instruction .= $this->getDebugHtml($key);
        Arr::set($acf, 'instructions', $instruction);
        return $acf;
    }

    /**
     * Get debug HTML helper
     *
     * @param string $key
     * @return string
     */
    public function getDebugHtml($key)
    {
        return sprintf(
            "
            <span style=\"
                border-radius: 0.25rem;
                background-color: #007cba;
                padding: 0.15rem 0.5rem;
                color: #fff;
                border: 1px solid #007cba;
                font-size: 11px;
                display: inline-block;
            \">
             %s
            </span>
        ",
            $key
        );
    }
}
