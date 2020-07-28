<?php

return [

    /**
     * You may enable this mode to display ACF keys
     * next to each field, useful for debugging
     */
    'debug' => false,

    /**
     * Enable/disable admin interface using boolean value.
     * Optionally receive an array to modify admin pages behaviour
     * - 'enabled' {boolean} Same effect as `'ui' => true`
     * - 'inline_assets' {boolean} Use this to inline admin styles and scripts if path to vendor/ is not public.
     */
    'ui' => false,

    /**
     * The path where all ACF fields are stored
     */
    'path' => get_stylesheet_directory() . '/acf-fields',

    /**
     * The entry point, listing all registered fields.
     */
    'entry' => 'index.yaml',

    /**
     * The parser responsible to translating ACF fields
     * into ACF field groups
     */
    'parser' => \Windsor\Parser\YamlParser::class,

    /**
     * List transform rules for each fields and groups
     */
    'rules' => [
        'fields' => [
            \Windsor\Rules\FieldDefaultsRule::class,
            \Windsor\Rules\WrapperShortcuts::class,
            \Windsor\Rules\FieldConditionRule::class,
            \Windsor\Rules\HelperRule::class,
        ],
        'groups' => [
            \Windsor\Rules\GroupLocationRule::class,
        ],
    ]

];
