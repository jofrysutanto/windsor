<?php

require "BaseTestCase.php";
require "ChangeInstructionsToFoo.php";
require "DummyHandler.php";

/**
 * Stub WordPress functions used by Windsor
 */
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

if (! function_exists('sanitize_title_with_dashes')) {
    /**
     * Sanitizes a title, replacing whitespace and a few other characters with dashes.
     *
     * @param string $title
     * @param string $raw_title
     * @param string $context
     * @return string
     */
    function sanitize_title_with_dashes($title, $raw_title = '', $context = 'display')
    {
        $title = strip_tags($title);
        // Preserve escaped octets.
        $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
        // Remove percent signs that are not part of an octet.
        $title = str_replace('%', '', $title);
        // Restore octets.
        $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

        if (seems_utf8($title)) {
            if (function_exists('mb_strtolower')) {
                $title = mb_strtolower($title, 'UTF-8');
            }
            $title = utf8_uri_encode($title, 200);
        }

        $title = strtolower($title);

        if ('save' == $context) {
            // Convert &nbsp, &ndash, and &mdash to hyphens.
            $title = str_replace(array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title);
            // Convert &nbsp, &ndash, and &mdash HTML entities to hyphens.
            $title = str_replace(array( '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ), '-', $title);
            // Convert forward slash to hyphen.
            $title = str_replace('/', '-', $title);

            // Strip these characters entirely.
            $title = str_replace(
                array(
                // Soft hyphens.
                '%c2%ad',
                // &iexcl and &iquest.
                '%c2%a1',
                '%c2%bf',
                // Angle quotes.
                '%c2%ab',
                '%c2%bb',
                '%e2%80%b9',
                '%e2%80%ba',
                // Curly quotes.
                '%e2%80%98',
                '%e2%80%99',
                '%e2%80%9c',
                '%e2%80%9d',
                '%e2%80%9a',
                '%e2%80%9b',
                '%e2%80%9e',
                '%e2%80%9f',
                // &copy, &reg, &deg, &hellip, and &trade.
                '%c2%a9',
                '%c2%ae',
                '%c2%b0',
                '%e2%80%a6',
                '%e2%84%a2',
                // Acute accents.
                '%c2%b4',
                '%cb%8a',
                '%cc%81',
                '%cd%81',
                // Grave accent, macron, caron.
                '%cc%80',
                '%cc%84',
                '%cc%8c',
            ),
                '',
                $title
            );

            // Convert &times to 'x'.
            $title = str_replace('%c3%97', 'x', $title);
        }

        // Kill entities.
        $title = preg_replace('/&.+?;/', '', $title);
        $title = str_replace('.', '-', $title);

        $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
        $title = preg_replace('/\s+/', '-', $title);
        $title = preg_replace('|-+|', '-', $title);
        $title = trim($title, '-');

        return $title;
    }
}
