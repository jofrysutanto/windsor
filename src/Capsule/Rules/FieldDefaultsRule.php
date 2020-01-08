<?php

namespace AcfYaml\Capsule\Rules;

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
        $type = array_get($acf, 'type');
        if (!$type) {
            // Default field type to text (commonly used)
            $acf['type'] = 'text';
        }
        // Shortcut to width
        if ($width = array_get($acf, 'width')) {
            unset($acf['width']);
            array_set($acf, 'wrapper.width', $width);
        }
        // Shortcut to class
        if ($class = array_get($acf, 'class')) {
            unset($acf['class']);
            array_set($acf, 'wrapper.class', $class);
        }
        // Typo resistance
        // - instruction -> instructions
        if ($instruction = array_get($acf, 'instruction')) {
            array_set($acf, 'instructions', $instruction);
            unset($acf['instruction']);
        }
        return $acf;
    }
}
