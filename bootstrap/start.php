<?php
// Bail early if running in CLI mode

use Windsor\Parser\Finder;
use Windsor\Support\Config;
use Windsor\Capsule\BlueprintsFactory;

if (PHP_SAPI == 'cli') {
    return;
}

/**
 * Start writing presentable and beautiful custom fields
 */
function register_windsor()
{
    if (!class_exists('ACF')) {
        // Bail early if ACF is not installed
        return;
    }
    $manager = new \Windsor\Capsule\Manager(
        Config::instance(),
        new Finder,
        BlueprintsFactory::instance()
    );
    $manager->register();
}
add_action('acf/init', 'register_windsor');
