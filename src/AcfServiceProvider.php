<?php

namespace AcfYaml;

use EvolveEngine\Core\ServiceProvider;
use AcfYaml\Capsule\Manager;

class AcfServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('acf.capsule', function ($app) {
            return new Manager($app['config']->get('acf-yaml', []));
        });

        // Using our capsulated ACF
        $this->app->action('acf/init', 'acf.capsule@register');
    }
}
