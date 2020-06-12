<?php
namespace Windsor\Rules;

use Tightenco\Collect\Support\Arr;

class WrapperShortcuts
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
        // Shortcut to width
        if ($width = Arr::get($acf, 'wrapper_width')) {
            Arr::forget($acf, 'wrapper_width');
            Arr::set($acf, 'wrapper.width', $width);
        }
        // Shortcut to 'class' attribute
        if ($class = Arr::get($acf, 'wrapper_class')) {
            Arr::forget($acf, 'wrapper_class');
            Arr::set($acf, 'wrapper.class', $class);
        }
        // Shortcut to 'id' attribute
        if ($class = Arr::get($acf, 'wrapper_id')) {
            Arr::forget($acf, 'wrapper_id');
            Arr::set($acf, 'wrapper.id', $class);
        }
        return $acf;
    }
}
