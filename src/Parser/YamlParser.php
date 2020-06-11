<?php
namespace Windsor\Parser;

use Symfony\Component\Yaml\Yaml;
use Windsor\Contracts\ParserContract;

class YamlParser implements ParserContract
{

    /**
     * Parse file content into fields array
     *
     * @param string $content
     * @return array
     */
    public function parse($content)
    {
        return Yaml::parse($content);
    }
}
