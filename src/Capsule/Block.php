<?php
namespace Windsor\Capsule;

use Tightenco\Collect\Support\Arr;
use Windsor\Capsule\BlueprintsFactory;

class Block extends AbstractCapsule
{

    /**
     * Templates repository
     *
     * @var \Windsor\Capsule\BlueprintsFactory
     */
    protected $templates;

    public function __construct()
    {
        $this->templates = BlueprintsFactory::instance();
    }

    /**
     * @inheritDoc
     */
    public function make($content)
    {
        $this->parsed = tap($content, function (&$block) {
            $block['name'] = $block['key'];
            unset($block['key']);
            unset($block['fields']);
            if ($handler = Arr::get($block, 'handler')) {
                $block = $this->attachHandler($handler, $block);
            }
        });
        return $this;
    }

    /**
     * Creates callable block handler
     *
     * @param string $handler classname
     * @param array $block
     *
     * @return \Windsor\AcfBlock
     */
    protected function attachHandler($handler, $block)
    {
        if (!class_exists($handler)) {
            return null;
        }
        $instance = new $handler($block);
        if (!$instance instanceof \Windsor\AcfBlock) {
            throw new \Exception("Invalid $handler — block handler should extend from Windsor\\AcfBlock");
        }
        $block['render_callback'] = [$instance, 'render'];
        $block['enqueue_assets'] = [$instance, 'enqueueAssets'];
        return $block;
    }
}
