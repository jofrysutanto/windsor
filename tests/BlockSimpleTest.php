<?php

use Tightenco\Collect\Support\Arr;

final class BlockSimpleTest extends BaseTestCase
{
    public function testSimple()
    {
        $manager = $this->getBlockManager('blocks/sample-block.acf.yaml');
        $fieldGroup = $this->pluckFirst($manager, 'parsed.field-group');
        $block = $this->pluckFirst($manager, 'parsed.block');
        $this->assertNull(Arr::get($block, 'fields'));
        $this->assertEquals('acf/myblock', Arr::get($fieldGroup, 'location.0.0.value'));
        $this->assertEquals('block', Arr::get($fieldGroup, 'location.0.0.param'));
        $this->assertEquals('==', Arr::get($fieldGroup, 'location.0.0.operator'));
        $this->assertEquals(1, count(Arr::get($fieldGroup, 'location')));
        $this->assertEquals(1, count(Arr::get($fieldGroup, 'location.0')));
    }

    public function testHandler()
    {
        $manager = $this->getBlockManager('blocks/block-with-handler.acf.yaml');
        $block = $this->pluckFirst($manager, 'parsed.block');
        $this->assertTrue(Arr::get($block, 'enqueue_assets.0') instanceof \Windsor\AcfBlock);
        $this->assertEquals('enqueueAssets', Arr::get($block, 'enqueue_assets.1'));
        $this->assertTrue(Arr::get($block, 'render_callback.0') instanceof \Windsor\AcfBlock);
        $this->assertEquals('render', Arr::get($block, 'render_callback.1'));
    }

    protected function getBlockManager($name)
    {
        return $this->makeManager([
            'finder' => $this->mockFinderEntry([
                'blocks' => [$name]
            ])
        ]);
    }
}
