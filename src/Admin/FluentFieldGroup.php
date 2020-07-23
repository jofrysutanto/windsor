<?php
namespace Windsor\Admin;

use Windsor\Support\Fluent;

class FluentFieldGroup extends Fluent
{

    /**
     * Create new fluent instance from raw field group
     *
     * @param array $data
     * @return self
     */
    public static function fromRawFieldGroup($data)
    {
        return new static(array_merge($data, [
            'count' => acf_get_field_count($data)
        ]));
    }
}
