<?php

final class KeysTest extends BaseTestCase
{
    public function testRepeaterKeys()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['keys.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            '__repeat_sub_1',
            $this->pluckFirst($manager, 'fields.0.sub_fields.0.key')
        );
        $this->assertEquals(
            'sub_1',
            $this->pluckFirst($manager, 'fields.0.sub_fields.0.name')
        );
        $this->assertEquals(
            '__repeat_sub_2_repeat_sub_sub_1',
            $this->pluckFirst($manager, 'fields.0.sub_fields.1.sub_fields.0.key')
        );
    }

    public function testFlexKeys()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['keys.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            '__flex_layout_1',
            $this->pluckFirst($manager, 'fields.1.layouts.0.key')
        );
        $this->assertEquals(
            '__flex_layout_1_layout_sub_1',
            $this->pluckFirst($manager, 'fields.1.layouts.0.sub_fields.0.key')
        );
    }
}
