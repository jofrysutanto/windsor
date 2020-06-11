<?php
namespace Windsor\Capsule;

use Tightenco\Collect\Support\Arr;

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
        $this->loadConfig();
        $this->finder
            ->setBasePath($this->config->get('path'))
            ->setIndex($this->config->get('entry'))
            ->setParser($this->config->get('parser'));

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
            ->setDebug(Arr::get($this->config, 'debug', false))
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
            $key = str_replace('.yaml', '', basename($def));
            $this->blueprints->store($key, $content);
        }
    }

    /**
     * Load configuration values
     *
     * @return void
     */
    protected function loadConfig()
    {
        $this->config->initialise();
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
