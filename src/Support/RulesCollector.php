<?php
namespace Windsor\Support;

use Tightenco\Collect\Contracts\Support\Arrayable;

class RulesCollector implements Arrayable
{

    /**
     * Transformation rules
     *
     * @var array
     */
    protected $rules = [];

    public function __construct($rules = [])
    {
        if ($rules instanceof Arrayable) {
            $this->setupRules($rules->toArray());
        } elseif (is_array($rules)) {
            $this->setupRules($rules);
        }
    }

    /**
     * Store transformation rules
     *
     * @param array $rules
     * @return \Windsor\Support\RulesCollector
     */
    public function setupRules($rules = [])
    {
        $this->rules = apply_filters('acf-windsor/config/rules', $rules);
        return $this;
    }

    /**
     * Retrieve transformation rules for given type
     *
     * @param string $key Either 'fields' or 'groups'
     * @return \Tightenco\Collect\Support\Collection|null
     */
    public function get($key)
    {
        if (!isset($this->rules[$key])) {
            return collect();
        }
        return collect($this->rules[$key]);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->rules;
    }
}
