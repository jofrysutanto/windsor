<?php
namespace Windsor;

abstract class AcfBlock
{

    /**
     * @var array ACF block configuration array
     */
    protected $block;

    public function __construct($block)
    {
        $this->block = $block;
    }

    /**
     * Runs whenever your block is displayed (front-end and back-end) and enqueues scripts and/or styles
     *
     * @return void
     */
    public function enqueueAssets()
    {
    }

    /**
     * Renders resulting HTML for current block
     *
     * @return void
     */
    public function render()
    {
    }
}
