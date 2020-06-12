<?php

use Windsor\Support\Config;

final class RulesTest extends BaseTestCase
{
    public function testDefaultRules()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/default-rules.acf.yaml']
            ])
        ]);
        $this->assertEquals([
            [
                'key' => '__field_1',
                'name' => 'field_1',
                'label' => 'Field 1',
                'type' => 'text',
            ],
            [
                'key' => '__field_2',
                'name' => 'field_2',
                'label' => 'Field 2',
                'type' => 'text',
                'instructions' => 'My instructions'
            ]
        ], $this->pluckFirst($manager, 'fields'));
    }

    public function testHiddenLocation()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/default-rules.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            (new \Windsor\Rules\GroupLocationRule)->getHiddenLocationRule(),
            $this->pluckFirst($manager, 'location')
        );
    }

    public function testHelper()
    {
        $manager = $this->makeManager([
            'config' => $this->mockConfig(['debug' => true]),
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/helper-rules.acf.yaml']
            ])
        ]);
        $helper = new \Windsor\Rules\HelperRule;
        $this->assertEquals(
            $helper->getDebugHtml('field_1'),
            $this->pluckFirst($manager, 'fields.0.instructions')
        );
        $this->assertEquals(
            'My sample instructions' . $helper->getDebugHtml('field_2'),
            $this->pluckFirst($manager, 'fields.1.instructions')
        );
    }

    public function testWrapperShortcuts()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/wrapper-rules.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            [
                'width' => 300,
                'class' => 'my-css',
                'id' => 'myUniqueId',
            ],
            $this->pluckFirst($manager, 'fields.0.wrapper')
        );
    }

    public function testCustomRules()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['sample-field.acf.yaml']
            ]),
            'config' => $this->mockConfig([
                'rules' => [
                    'fields' => [
                        ChangeInstructionsToFoo::class
                    ]
                ]
            ])
        ]);
        $this->assertEquals(
            'foo',
            $this->pluckFirst($manager, 'fields.0.instructions')
        );
        $this->assertEquals(
            'foo',
            $this->pluckFirst($manager, 'fields.1.instructions')
        );
    }
}
