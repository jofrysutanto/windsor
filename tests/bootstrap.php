<?php

require "BaseTestCase.php";
require "ChangeInstructionsToFoo.php";

if (! function_exists('apply_filters')) {
    /**
     * Stub WordPress `apply_filters`
     */
    function apply_filters($filter, $argument)
    {
        return $argument;
    }
}

if (! function_exists('get_stylesheet_directory')) {
    /**
     * Stub WordPress `get_stylesheet_directory`
     */
    function get_stylesheet_directory()
    {
        return __DIR__ . '/yaml';
    }
}
