<?php
namespace Windsor\Parser;

class Finder
{

    /**
     * Root directory
     *
     * @var string
     */
    protected $root;

    /**
     * Filename of entry point
     *
     * @var string
     */
    protected $entry;

    /**
     * @var \Windsor\Contracts\ParserContract
     */
    protected $parser;

    /**
     * Set parser
     *
     * @param Reader $parser
     * @return $this
     */
    public function setParser($parser)
    {
        if (is_string($parser) && class_exists($parser)) {
            $parser = apply_filters('acf-windsor/init_parser', new $parser);
        }
        $this->parser = $parser;
        return $this;
    }

    /**
     * Set entry point for acf fields
     *
     * @param string
     * @return $this
     */
    public function setIndex($name)
    {
        $this->entry = $name;
        return $this;
    }

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
        return $this->readFile($this->path($this->entry));
    }

    /**
     * Set base path to find ACF fields
     *
     * @param string $path
     * @return $this
     */
    public function setBasePath($path)
    {
        $this->root = $path;
        return $this;
    }

    /**
     * Get absolute path of given definition file name
     *
     * @param string $name
     * @return string
     */
    protected function path($name)
    {
        return $this->root . '/' . ltrim($name, '/');
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
        return $this->parser->parse(file_get_contents($filepath));
    }
}
