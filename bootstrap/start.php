<?php
use Windsor\Parser\Finder;
use Windsor\Support\Config;
use Windsor\Capsule\BlueprintsFactory;

// Bail early if running in CLI mode
if (PHP_SAPI == 'cli') {
    return;
}

/**
 * Start writing presentable and beautiful custom fields
 */
function register_windsor()
{
    if (!class_exists('ACF')) {
        // Bail early if ACF is not available
        return;
    }
    $manager = new \Windsor\Capsule\Manager(
        Config::initialise(),
        new Finder,
        BlueprintsFactory::instance()
    );
    $manager->register();
}
add_action('acf/init', 'register_windsor');
