<?php
namespace Windsor\Admin\Exporter;

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
        $packer = new FieldsPacker($this->field);
        $result = $packer
            ->setMode($this->mode)
            ->pack();
        // dump($result);
        $result = Yaml::dump($result, 50, 2);
        return $result;
    }
}
