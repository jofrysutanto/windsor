<?php

require "BaseTestCase.php";
require "ChangeInstructionsToFoo.php";

if (! function_exists('apply_filters')) {
    /**
     * Stub Wordpress `apply_filters`
     */
    function apply_filters($filter, $argument)
    {
        return $argument;
    }
}

if (! function_exists('get_stylesheet_directory')) {
    /**
     * Stub Wordpress `get_stylesheet_directory`
     */
    function get_stylesheet_directory()
    {
        return __DIR__ . '/yaml';
    }
}
