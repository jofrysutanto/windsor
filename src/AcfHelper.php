<?php

namespace AcfYaml;

use AcfYaml\Support\Singleton;

class AcfHelper
{
    use Singleton;

    /**
     * Convert flexible content field, to combination of collection/fluent field
     *
     * @param  mixed $flexibleContent
     *
     * @return Collection
     */
    public function flex($flexibleContent)
    {
        if (!is_array($flexibleContent)) {
            return collect();
        }

        $flexibleContent = collect($flexibleContent)
            ->transform(function ($item) {
                $item = fluent($item);
                $item->layout = $item->acf_fc_layout;
                return $item;
            });

        return $flexibleContent;
    }

    /**
     * Retrieve image url for given field id or array
     *
     * @param  mixed $field
     * @param  mixed $postId
     *
     * @return string
     */
    public function image($field, $postId = null)
    {
        if (is_string($field)) {
            $field = get_field($field, $postId);
        }
        if (!$field) {
            return null;
        }
        if (isset($field['url'])) {
            return $field['url'];
        }
        return null;
    }
}
