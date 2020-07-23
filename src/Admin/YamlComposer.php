<?php
namespace Windsor\Admin;

use Symfony\Component\Yaml\Yaml;

class YamlComposer
{

    /**
     * @var string 'full'|'compact'
     */
    protected $mode;

    /**
     * @var array Array representation of field group
     */
    protected $field;

    public function __construct($field, $mode = 'full')
    {
        $this->field = $field;
        $this->mode = $mode;
    }

    /**
     * Generate YAML string
     *
     * @return string
     */
    public function generate()
    {
        $result = Yaml::dump($this->field, 50, 2);
        return $result;
    }
}
