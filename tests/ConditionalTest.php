<?php

final class ConditionalTest extends BaseTestCase
{
    public function testConditionalPrefix()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/conditional-rules.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            '__toggle',
            $this->pluckFirst($manager, 'fields.1.conditional_logic.0.0.field')
        );
    }

    public function testConditionalValue()
    {
        $manager = $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'fields' => ['rules/conditional-rules.acf.yaml']
            ])
        ]);
        $this->assertEquals(
            '1',
            $this->pluckFirst($manager, 'fields.1.conditional_logic.0.0.value')
        );
        $this->assertNotTrue(
            $this->pluckFirst($manager, 'fields.1.conditional_logic.0.0.value')
        );
    }
}
