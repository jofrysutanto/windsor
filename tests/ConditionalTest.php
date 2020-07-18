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
            $this->pluckFirst($manager, 'parsed.fields.1.conditional_logic.0.0.field')
        );
        $this->assertEquals(
            '__toggle',
            $this->pluckFirst($manager, 'parsed.fields.2.conditional_logic.0.0.field')
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
            $this->pluckFirst($manager, 'parsed.fields.1.conditional_logic.0.0.value')
        );
        $this->assertNotTrue(
            $this->pluckFirst($manager, 'parsed.fields.1.conditional_logic.0.0.value')
        );
    }
}
