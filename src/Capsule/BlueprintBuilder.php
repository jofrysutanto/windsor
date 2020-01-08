<?php

namespace AcfYaml\Capsule;

use AcfYaml\Capsule\Utilities\PrefixConditionalLogic;

class BlueprintBuilder
{

    /**
     * Template fields configuration,
     * containing fields which can be copied
     *
     * @var Fluent
     */
    protected $config;

    /**
     * Key of the fields to be copied to
     *
     * @var string
     */
    protected $key;

    /**
     * Parameters of the fields to be copied to
     *
     * @var array
     */
    protected $fields;

    public function __construct(array $config, $key, $fields)
    {
        $this->config = new \Illuminate\Support\Fluent($config);
        $this->key = $key;
        $this->fields = $fields;
    }

    /**
     * Creates a copy of the template based on
     * stored configurations
     *
     * @return array
     */
    public function makeCopy(): array
    {
        $keys = $this->collectKeys();
        $result = [];
        foreach ($keys as $key) {
            $field = array_get($this->config->fields, $key);
            if (!$field) {
                continue;
            }
            $result[$key] = $field;
        }

        // Prefix labels if necessary
        $prefixLabel = array_get($this->fields, 'prefix_label', false);
        if ($prefixLabel) {
            $result = array_map(function ($field) {
                $label = array_get($field, 'label');
                if (!$label) {
                    return $field;
                }
                $prefix = array_get($this->fields, 'label');
                $field['label'] = $prefix . ' ' . $label;
                return $field;
            }, $result);
        }

        // Merge fields
        // Additional fields can be added, or existing fields can be overwritten
        // by YAML config using the `merge` key
        $mergeFields = array_get($this->fields, 'merge', []);
        if (count($mergeFields) > 0) {
            $result = array_replace_recursive($result, $mergeFields);
        }

        // Layout
        $layout = array_get($this->fields, 'layout', []);
        if (count($layout) > 0) {
            $result = $this->reLayout($result, $layout);
        }

        // Finally return result while optionally prefixing keys
        return $this->prefixAllKeys($result);
    }

    /**
     * Filter keys to be included in template
     *
     * @param array $template
     * @param array $fields
     * @return array
     */
    protected function collectKeys()
    {
        if ($keys = array_get($this->fields, 'only')) {
            return $keys;
        }
        $fieldKeys = array_keys(array_get($this->config, 'fields'));
        if ($excludes = array_get($this->fields, 'excludes')) {
            return array_diff($fieldKeys, $excludes);
        }
        return $fieldKeys;
    }

    /**
     * Re-organise the layout of the copied fields
     * Fields can be re-ordered, and also resized by using the following convention
     * ```
     * layout:
     * - field1@50 : where `field1` is the name of the key, and `50` is the width %
     * - field2@50
     * ```
     * The items above are ordered according to the index. Any missing fields will be appended last.
     *
     * @param array $fields
     * @param array $layout
     * @return array
     */
    protected function reLayout($fields, $layout)
    {
        $result = [];
        foreach ($layout as $layoutConfig) {
            $fragments = explode('@', $layoutConfig);
            if (count($fragments) <= 0) {
                continue;
            }
            $key = $fragments[0];
            if (!isset($fields[$key])) {
                continue;
            }
            $width = null;
            if (count($fragments) > 1) {
                $width = $fragments[1];
            }
            $result[$key] = array_pull($fields, $key);
            if ($width) {
                $result[$key]['width'] = $width;
            }
        }
        // If there are left overs, we simply append them to the end of resulting array
        if (count($fields) > 0) {
            $result = $result + $fields;
        }
        return $result;
    }

    /**
     * Prefix all keys in the array
     *
     * @param array $beforeArray
     * @return array
     */
    protected function prefixAllKeys($beforeArray = [])
    {
        $result = [];
        $prefix = $this->getPrefix();
        if ($prefix === '') {
            return $beforeArray;
        }
        foreach ($beforeArray as $key => $value) {
            $newKey = $prefix . $key;
            $value = $this->processConditionalLogic($prefix, $value);
            $result[$newKey] = $value;
        }
        
        return $result;
    }

    protected function processConditionalLogic($prefix, $acf)
    {
        $prefixer = new PrefixConditionalLogic;
        return $prefixer->run($acf, $prefix);
    }

    /**
     * Retrieve prefix to be used for current copy operation
     *
     * @return string
     */
    protected function getPrefix()
    {
        $prefix = array_get($this->fields, 'prefix', false);
        if ($prefix === false) {
            return '';
        }
        // If 'prefix' is `true`, it is implied that prefix uses parent key
        if ($prefix === true) {
            $prefix = $this->key;
        }
        return $prefix . '_';
    }
}
