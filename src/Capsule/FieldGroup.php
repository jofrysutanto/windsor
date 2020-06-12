<?php
namespace Windsor\Capsule;

use Tightenco\Collect\Support\Arr;
use Windsor\Support\RulesCollector;
use Windsor\Capsule\BlueprintsFactory;

class FieldGroup
{
    public static $namespace = [];

    const TYPE_FIELD_GROUP = 'FIELD_GROUP';
    const TYPE_REPEATER = 'REPEATER';
    const TYPE_GROUP = 'GROUP';
    const TYPE_FLEX = 'FLEXIBLE_CONTENT';

    /**
     * @var array Raw content of ACF definition file
     */
    protected $content;

    /**
     * @var array Valid ACF field according to ACF's standard
     */
    protected $parsed;

    /**
     * Type of field group
     *
     * @var string
     */
    protected $type;

    /**
     * Templates repository
     *
     * @var \Windsor\Capsule\BlueprintsFactory
     */
    protected $templates;

    /**
     * Rules collection
     *
     * @var \Windsor\Support\RulesCollector
     */
    protected $rules;

    /**
     * Debug mode flag
     *
     * @var boolean
     */
    protected $debug = false;

    public function __construct($type)
    {
        $this->type = $type;
        $this->templates = BlueprintsFactory::instance();
    }

    /**
     * Create and parse group with given content
     *
     * @param array $content
     * @return \Windsor\Capsule\FieldGroup
     */
    public function make($content)
    {
        $this->namespace(Arr::get($content, 'key'), function () use ($content) {
            $this->content = $content;
            $this->parsed = tap($content, function (&$group) {
                $group = $this->parseGroup($group);
                if ($this->type === static::TYPE_FLEX) {
                    $group['layouts'] = $this->parseLayouts($group);
                } else {
                    $group[$this->getFieldsKey()] = $this->parseFields($group);
                }
            });
        });
        return $this;
    }

    /**
     * Retrieve parsed and valid ACF array
     *
     * @return array
     */
    public function parsed()
    {
        return $this->parsed;
    }

    /**
     * Group and scope all fields to given namespace.
     * The namespace is used to prefix all fields to ensure their uniqueness.
     *
     * @param string $namespace
     * @param Closure $callback
     * @return \Windsor\Capsule\FieldGroup
     */
    public function namespace($namespace, $callback)
    {
        static::$namespace[] = $namespace;
        $callback($this);
        array_pop(static::$namespace);
        return $this;
    }

    /**
     * Parse group-type content
     *
     * @param array $group
     * @return array
     */
    protected function parseGroup($group)
    {
        $this->rules
            ->get('groups')
            ->each(function ($rule) use (&$group) {
                $group = (new $rule)->process($this, '', $group);
            });
        return $group;
    }

    /**
     * Parse collection of ACF fields
     *
     * @param array $group
     * @return array
     */
    protected function parseFields($group)
    {
        $fields = [];
        $yamlFields = Arr::get($group, $this->getFieldsKey(), []);
        if (!$yamlFields) {
            return [];
        }

        // If templates, merge definitions
        $yamlFields = $this->templates->mergeBlueprints($yamlFields);

        foreach ($yamlFields as $key => $value) {
            $fields[] = $this->makeField($key, $value);
        }
        return $fields;
    }

    /**
     * Parse flexible content layouts
     * which in essence parse nested repeaters
     *
     * @param array $group
     * @return array
     */
    protected function parseLayouts($group)
    {
        $parsedLayouts = [];
        $layouts = Arr::get($group, 'layouts', []);
        foreach ($layouts as $layoutKey => $layoutConfig) {
            // dump($this->makeKey($layoutKey));
            // dump($layoutKey);
            $parsedLayout = $this->makeField($layoutKey, $layoutConfig);
            // We don't need 'type' for layouts
            unset($parsedLayout['type']);
            $yamlFields = Arr::get($parsedLayout, 'sub_fields', []);
            $yamlFields = $this->templates->mergeBlueprints($yamlFields);

            $fields = [];
            $this->namespace($this->makeKey($layoutKey), function () use ($yamlFields, &$fields) {
                foreach ($yamlFields as $key => $value) {
                    $fields[] = $this->makeField($key, $value);
                }
            });

            $parsedLayout['sub_fields'] = $fields;
            $parsedLayouts[] = $parsedLayout;
        }
        return $parsedLayouts;
    }

    /**
     * Creates ACF-valid field
     *
     * @param string $key
     * @param array $value
     * @return array
     */
    protected function makeField($key, $value)
    {
        $uniqueKey = $this->makeKey($key);
        $value = array_merge($value, [
            'name' => $key,
            'key'  => $uniqueKey
        ]);

        if ($groupType = $this->getGroupType(Arr::get($value, 'type'))) {
            $value = (new FieldGroup($groupType))
                ->setDebug($this->isDebugging())
                ->setRules(new RulesCollector($this->rules))
                ->make($value)
                ->parsed();
        }

        $this->rules
            ->get('fields')
            ->each(function ($rule) use ($key, &$value) {
                $value = (new $rule)->process($this, $key, $value);
            });

        return $value;
    }

    /**
     * Generate unique key based on current active namespace
     *
     * @param string $key
     * @return string
     */
    public function makeKey($key)
    {
        if (count(static::$namespace) <= 0) {
            return $key;
        }
        if (starts_with($key, '~')) {
            return ltrim($key, '~');
        }
        $namespace = array_values(array_slice(static::$namespace, -1))[0];
        return $namespace . '_' . $key;
    }

    /**
     * Set debug mode
     *
     * @param boolean $isDebugging
     * @return \Windsor\Capsule\FieldGroup
     */
    public function setDebug($isDebugging = true)
    {
        $this->debug = $isDebugging;
        return $this;
    }

    /**
     * Set transformation rules
     *
     * @param \Windsor\Support\RulesCollector $rules
     * @return \Windsor\Capsule\FieldGroup
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Check debug mode
     *
     * @return boolean
     */
    public function isDebugging()
    {
        return $this->debug;
    }

    /**
     * Retrieve associative keys of fields based on active group type
     *
     * @return string
     */
    protected function getFieldsKey()
    {
        switch ($this->type) {
            case static::TYPE_FIELD_GROUP:
                return 'fields';
                break;
            case static::TYPE_REPEATER:
            case static::TYPE_GROUP:
                return 'sub_fields';
                break;
            case static::TYPE_FLEX:
                return 'layouts';
                break;
            default:
                throw new \Exception("Invalid type: $this->type");
                break;
        }
    }

    /**
     * Return 'grouping' type field, some fields
     * are container of other fields
     *
     * @param string $type
     * @return string|null
     */
    protected function getGroupType($type)
    {
        $groupType = null;
        switch ($type) {
            case 'repeater':
                $groupType = static::TYPE_REPEATER;
                break;
            case 'group':
                $groupType = static::TYPE_GROUP;
                break;
            case 'flexible_content':
                $groupType = static::TYPE_FLEX;
                break;
            default:
                break;
        }
        return $groupType;
    }
}
