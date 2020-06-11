<?php
use Mockery as m;
use Windsor\Parser\Finder;
use Windsor\Capsule\Manager;
use Windsor\Capsule\BlueprintsFactory;

final class SetupTest extends BaseTestCase
{
    public function testCreatingManagerInstance()
    {
        $config = $this->mockConfig([
            'entry' => 'index-empty.yaml'
        ]);
        $manager = new Manager(
            $config,
            new Finder,
            BlueprintsFactory::instance()
        );
        $manager->register();
    }

    public function testReadSimpleYaml()
    {
        $config = $this->mockConfig();
        $finder = $this->mockFinderEntry([
            'pages' => ['simple.acf.yaml']
        ]);
        $manager = new Manager(
            $config,
            $finder,
            BlueprintsFactory::instance()
        );
        $this->assertEquals([
            'title' => 'Simple Field Group',
            'key' => 'simple_field_group',
            'location' => null,
            'fields' => [
                [
                    'type' => 'text',
                    'label' => 'Heading',
                    'name' => 'heading',
                    'key' => 'simple_field_group_heading'
                ]
            ]
        ], $manager->build()->first());
    }
}
