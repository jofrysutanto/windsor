<?php
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Windsor\Parser\Finder;

class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function mockFinderEntry($return)
    {
        $finder = m::mock(Finder::class)->makePartial();
        $finder->shouldReceive('index')
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
        $config = m::mock(\Windsor\Support\Config::class)->makePartial();
        $config
            ->shouldReceive('initialise')
            ->once();
        $params = array_merge($this->defaultTestConfig(), $params);
        foreach ($params as $key => $value) {
            $config
                ->shouldReceive('get')
                ->with($key)
                ->andReturn($value);
        }
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
        ];
    }
}
