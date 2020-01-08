<?php

namespace AcfYaml\Capsule;

use Symfony\Component\Yaml\Yaml;

class Finder
{
    /**
     * Read a definition file content
     *
     * @param string $name
     * @return array
     */
    public function read($name)
    {
        return $this->readFile($this->path($name));
    }

    /**
     * Retrieve index of all definition files
     *
     * @return array
     */
    public function index()
    {
        return $this->readFile($this->path('index.yaml'));
    }

    /**
     * Get absolute path of given definition file name
     *
     * @param string $name
     * @return string
     */
    protected function path($name)
    {
        return base_path('resources/acf-fields/' . $name);
    }

    /**
     * Read and parse given file into array
     *
     * @param string $filepath
     * @return array
     */
    protected function readFile($filepath)
    {
        if (!file_exists($filepath)) {
            throw new \Exception("ACF Yaml file not found at $filepath");
        }
        return Yaml::parse(file_get_contents($filepath));
    }
}
