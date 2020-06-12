<?php
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tightenco\Collect\Support\Arr;
use Windsor\Capsule\BlueprintsFactory;
use Windsor\Parser\Finder;
use Windsor\Support\Config;

class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function pluckFirst($manager, $key = null)
    {
        $first = $manager->build()->first();
        if (is_null($key)) {
            return $first;
        }
        return Arr::get($first, $key);
    }

    /**
    * Convenient shortcut to create new Manager instance,
    * while allowing mocked configurations
    *
    * @param array $params
    * @return \Windsor\Capsule\Manager
    */
    protected function makeManager($params = [])
    {
        return new \Windsor\Capsule\Manager(
            (isset($params['config']) ? $params['config'] : $this->mockConfig()),
            (isset($params['finder']) ? $params['finder'] : new Finder),
            (isset($params['blueprints']) ? $params['blueprints'] : BlueprintsFactory::instance())
        );
    }

    /**
    * Convenient way to force finder to only index specific files
    *
    * @param array $return
    * @return object
    */
    protected function mockFinderEntry($return)
    {
        $finder = m::mock(Finder::class)->makePartial();
        $finder->shouldReceive('index')
        ->andReturn($return);
        return $finder;
    }

    /**
    * Convenient way to force finder to return specific array fields
    *
    * @param array $return
    * @return object
    */
    protected function mockFinderFields($return)
    {
        $finder = m::mock(Finder::class)->makePartial();
        $finder
            ->shouldReceive('index')
            ->andReturn($return);
        return $finder;
    }

    /**
    * Creates mocked config
    *
    * @param array $params
    * @return mixed
    */
    protected function mockConfig($params = [])
    {
        $config = new Config(array_merge(
            $this->defaultTestConfig(),
            $params
        ));
        return $config;
    }

    /**
    * Get default configuration for testing
    *
    * @return array
    */
    protected function defaultTestConfig()
    {
        return [
            'path'   => __DIR__ . '/yaml',
            'entry'  => 'index.yaml',
            'parser' => \Windsor\Parser\YamlParser::class,
            'rules' => [
                'fields' => [
                    \Windsor\Rules\FieldDefaultsRule::class,
                    \Windsor\Rules\WrapperShortcuts::class,
                    \Windsor\Rules\FieldConditionRule::class,
                    \Windsor\Rules\HelperRule::class,
                ],
                'groups' => [
                    \Windsor\Rules\GroupLocationRule::class,
                ],
            ]
        ];
    }
}
