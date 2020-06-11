<?php
namespace Windsor\Contracts;

interface ParserContract
{

    /**
     * Parse file content into fields array
     *
     * @param string $content
     * @return array
     */
    public function parse($content);
}
