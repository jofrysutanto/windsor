<?php
namespace Windsor\Capsule;

use Windsor\Parser\Finder;
use Windsor\Support\Config;
use Tightenco\Collect\Support\Arr;
use Windsor\Support\RulesCollector;
use Windsor\Admin\WordPress\UiLoader;

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
     * Create default instance with optional configurations
     * @link https://windsor-docs.netlify.app/configurations.html
     *
     * @param array $config Additional configuration.
     *
     * @return \Windsor\Capsule\Manager
     */
    public static function make(array $config = [])
    {
        return new static(
            Config::initialise($config),
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
        $this->maybeLoadUI();
        $this->build()
            ->each(function ($parsed) {
                if ($parsed['type'] === 'field-group') {
                    acf_add_local_field_group($parsed['parsed']);
                } elseif ($parsed['type'] === 'block') {
                    if (function_exists('acf_register_block_type')) {
                        acf_register_block_type(Arr::get($parsed, 'parsed.block'));
                    }
                    acf_add_local_field_group(Arr::get($parsed, 'parsed.field-group'));
                }
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
                    $parsed = $this->readFieldGroup($def);
                    $results->push([
                        'type' => 'field-group',
                        'parsed' => $parsed
                    ]);
                });
        }
        foreach (['blocks'] as $type) {
            collect($this->getDefinitions($type))
                ->each(function ($def) use ($results) {
                    $parsed = $this->readBlock($def);
                    $results->push([
                        'type' => 'block',
                        'parsed' => [
                            'field-group' => $parsed['field-group'],
                            'block' => $parsed['block'],
                        ]
                    ]);
                });
        }
        return $results;
    }

    /**
     * Reads an ACF field group definition file
     *
     * @param string $def
     * @return array Array containing ACF fields as-per ACF definitions
     */
    public function readFieldGroup($def)
    {
        if (is_string($def)) {
            $def = $this->finder->read($def);
        }

        $result = (new FieldGroup(FieldGroup::TYPE_FIELD_GROUP))
            ->setRules(
                new RulesCollector($this->config('rules', []))
            )
            ->setDebug($this->config('debug', false))
            ->make($def)
            ->parsed();

        return $result;
    }

    /**
     * Reads an ACF block definition file
     *
     * @param string $def
     * @return array Array containing ACF fields as-per ACF definitions
     */
    public function readBlock($def)
    {
        $content = $this->finder->read($def);
        $blockDefinition = (new Block())
            ->setDebug($this->config('debug', false))
            ->make($content)
            ->parsed();
        $fieldGroupsDefinition = array_merge($content, [
            'location' => [
                [
                    [
                        'param' => 'block',
                        'operator' => '==',
                        'value' => sprintf("acf/%s", $content['key'])
                    ]
                ]
            ]
        ]);
        return [
            'field-group' => $this->readFieldGroup($fieldGroupsDefinition),
            'block' => $blockDefinition
        ];
    }

    /**
     * Optionally initiate UI (Admin) components of Windsor
     *
     * @return void
     */
    protected function maybeLoadUI()
    {
        $uiConfig = $this->config('ui');
        if (!$uiConfig) {
            return;
        }
        if (!is_array($uiConfig)) {
            $uiConfig = [];
        }
        if (Arr::get($uiConfig, 'enabled') === false) {
            return;
        }
        $ui = new UiLoader($uiConfig);
        $ui->boot($uiConfig);
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
