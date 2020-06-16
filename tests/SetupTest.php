<?php

final class SetupTest extends BaseTestCase
{
    public function testManageMake()
    {
        $instance = Windsor\Capsule\Manager::make();
        $this->assertTrue($instance instanceof Windsor\Capsule\Manager);
    }

    public function testReadSimpleYaml()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'pages' => ['simple.acf.yaml']
            ])
        ]);
        $this->assertEquals([
            'title'    => 'Simple Field Group',
            'key'      => 'simple_field_group',
            'location' => (new \Windsor\Rules\GroupLocationRule)
                ->getHiddenLocationRule(),
            'fields' => [
                [
                    'type'  => 'text',
                    'label' => 'Heading',
                    'name'  => 'heading',
                    'key'   => 'simple_field_group_heading'
                ]
            ]
        ], $manager->build()->first());
    }

    public function testSubEntry()
    {
        $manager = $this->makeManager([
            'config' => $this->mockConfig([
                'path' => __DIR__ . '/yaml/sub',
            ])
        ]);

        $this->assertEquals([
            'title'    => 'Simple Field Group',
            'key'      => 'simple_field_group',
            'location' => (new \Windsor\Rules\GroupLocationRule)->getHiddenLocationRule(),
            'fields' => [
                [
                    'type'  => 'text',
                    'label' => 'Heading',
                    'name'  => 'heading',
                    'key'   => 'simple_field_group_heading'
                ]
            ]
        ], $manager->build()->first());
    }
}
