<?php
namespace Windsor\Admin\Exporter;

use Symfony\Component\Yaml\Yaml;

class YamlComposer
{

    /**
     * @var integer Indentation in spaces
     */
    protected $indent;

    public function __construct($indent = 2)
    {
        $this->indent = $indent;
    }

    /**
     * Generate YAML string
     *
     * @return string
     */
    public function generate($array)
    {
        $result = Yaml::dump(
            $array,
            50,
            $this->indent,
            Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE
        );
        return $result;
    }
}
