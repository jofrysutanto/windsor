<?php
namespace Windsor\Rules;

use Tightenco\Collect\Support\Arr;

class FieldDefaultsRule
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
        $type = Arr::get($acf, 'type');
        // Default field type to text (commonly used)
        if (!$type) {
            $acf['type'] = 'text';
        }
        // Typo resistance
        // - instruction -> instructions
        if ($instruction = Arr::get($acf, 'instruction')) {
            Arr::set($acf, 'instructions', $instruction);
            unset($acf['instruction']);
        }
        return $acf;
    }
}
