<?php
namespace Windsor\Capsule;

use Windsor\Parser\Finder;
use Windsor\Support\Config;
use Tightenco\Collect\Support\Arr;
use Windsor\Support\RulesCollector;

class Manager
{
    /**
     * @var \Windsor\Parser\Finder
     */
    protected $finder;

    /**
     * @var \Windsor\Capsule\BlueprintsFactory
     */
    protected $blueprints;

    /**
     * @var \Windsor\Support\Config
     */
    protected $config;

    public function __construct($config, $finder, $blueprints)
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->blueprints = $blueprints;
    }

    /**
     * Create default instance
     *
     * @return \Windsor\Capsule\Manager
     */
    public static function make()
    {
        return new static(
            Config::initialise(),
            new Finder,
            BlueprintsFactory::instance()
        );
    }

    /**
     * Register our in-code ACF fields
     *
     * @param array $config
     * @return void
     */
    public function register()
    {
        $this->build()
            ->each(function ($parsed) {
                acf_add_local_field_group($parsed);
            });
    }

    /**
     * Build and return parsed ACF fields
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function build()
    {
        $this->finder
            ->setBasePath($this->config('path'))
            ->setIndex($this->config('entry'))
            ->setParser($this->config('parser'));

        // Collect all reusable blueprints
        $this->storeBlueprints(
            $this->getDefinitions('blueprints')
        );

        $results = collect();
        foreach (['fields', 'pages'] as $type) {
            collect($this->getDefinitions($type))
                ->each(function ($def) use ($results) {
                    $parsed = $this->read($def);
                    $results->push($parsed);
                });
        }
        return $results;
    }

    /**
     * Read an ACF definition file
     *
     * @param string $def
     * @return array Array containing ACF fields as-per ACF definitions
     */
    public function read($def)
    {
        $content = $this->finder->read($def);

        $result = (new FieldGroup(FieldGroup::TYPE_FIELD_GROUP))
            ->setDebug($this->config('debug', false))
            ->setRules(
                new RulesCollector($this->config('rules', []))
            )
            ->make($content)
            ->parsed();

        return $result;
    }

    /**
     * Store blueprints data
     *
     * @param array $data
     * @return void
     */
    protected function storeBlueprints(array $data = [])
    {
        foreach ($data as $def) {
            $content = $this->finder->read($def);
            $key = str_replace('.acf.yaml', '', basename($def));
            $this->blueprints->store($key, $content);
        }
    }

    /**
     * Retrieve stored configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Retrieve locations of ACF definitions
     *
     * @return array
     */
    protected function getDefinitions($type)
    {
        try {
            $fields = $this->finder->index();
            return Arr::get($fields, $type, []);
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }
}
