<?php

use Tightenco\Collect\Support\Arr;

final class BlueprintsTest extends BaseTestCase
{
    public function testDefault()
    {
        $manager = $this->getBlueprintManager('blueprints/test-default.acf.yaml');
        $this->assertEquals(
            [
                'type' => 'text',
                'label' => 'Heading',
                'name' => 'heading',
                'key' => '__heading',
                'wrapper' => [
                    'width' => 50
                ]
            ],
            $this->pluckFirst($manager, 'fields.0')
        );
        $this->assertEquals(
            '__use_image',
            $this->pluckFirst($manager, 'fields.2.conditional_logic.0.0.field')
        );
    }

    public function testExclude()
    {
        $manager = $this->getBlueprintManager('blueprints/test-exclude.acf.yaml');
        $fieldKeys = Arr::pluck($this->pluckFirst($manager, 'fields'), 'name');
        $this->assertEquals(
            ['use_image', 'image'],
            $fieldKeys
        );
    }

    public function testOnly()
    {
        $manager = $this->getBlueprintManager('blueprints/test-only.acf.yaml');
        $fieldKeys = Arr::pluck($this->pluckFirst($manager, 'fields'), 'name');
        $this->assertEquals(
            ['heading'],
            $fieldKeys
        );
    }

    public function testLayout()
    {
        $manager = $this->getBlueprintManager('blueprints/test-layout.acf.yaml');
        $fieldKeys = Arr::pluck($this->pluckFirst($manager, 'fields'), 'name');
        $fieldWidths = Arr::pluck($this->pluckFirst($manager, 'fields'), 'wrapper.width');
        $this->assertEquals(
            [
                'use_image',
                'image',
                'heading',
                'description',
            ],
            $fieldKeys
        );
        $this->assertEquals(
            [
                50,
                50,
                40,
                60,
            ],
            $fieldWidths
        );
    }

    public function testMerge()
    {
        $manager = $this->getBlueprintManager('blueprints/test-layout.acf.yaml');
        $fields = $this->pluckFirst($manager, 'fields');
        $count = count($fields);
        $last = array_pop($fields);
        $this->assertEquals('description', $last['name']);
        $this->assertEquals(4, $count);
    }

    public function testPrefix()
    {
        $manager = $this->getBlueprintManager('blueprints/test-prefix.acf.yaml');
        $fields = $this->pluckFirst($manager, 'fields');
        $this->assertEquals(
            'Merge Heading',
            $fields[0]['label']
        );
        $this->assertEquals(
            'merge_prefix_heading',
            $fields[0]['name']
        );
        $this->assertEquals(
            'Merge Image',
            $fields[2]['label']
        );

        $this->assertEquals(
            'merge_prefix_image',
            $fields[2]['name']
        );
        $this->assertEquals(
            '__merge_prefix_use_image',
            $fields[2]['conditional_logic'][0][0]['field']
        );
    }

    protected function getBlueprintManager($name)
    {
        return $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => [$name],
                'blueprints' => ['blueprints/my-blueprint.acf.yaml']
            ])
        ]);
    }
}
