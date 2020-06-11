<?php

require "BaseTestCase.php";

if (! function_exists('apply_filters')) {
    /**
     * Mock Wordpress `apply_filters`
     */
    function apply_filters($filter, $argument)
    {
        return $argument;
    }
}
