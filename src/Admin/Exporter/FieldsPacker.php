<?php
namespace Windsor\Admin\Exporter;

use Tightenco\Collect\Support\Arr;
use Tightenco\Collect\Support\Collection;

class FieldsPacker
{

    /**
     * @var array Raw field group data
     */
    protected $raw;

    /**
     * @var string Packing mode.
     * - 'full' for default packing (leave fields as is)
     * - 'compact' to remove empty or fields with default values
     */
    protected $mode = 'full';

    protected $compactRulesByType = [
        'image' => CompactRules\CompactImage::class,
    ];

    public function __construct($raw = [])
    {
        $this->raw = $raw;
    }

    /**
     * Set packing mode
     *
     * @param string $mode 'full' or 'compact'
     * @return self
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Check if we are using compact mode
     *
     * @return boolean
     */
    public function isCompact()
    {
        return $this->mode === 'compact';
    }

    /**
     * Start packing process
     *
     * @return array
     */
    public function pack()
    {
        return (new MutableField($this->raw))
            ->shiftToLast('fields')
            ->when($this->isCompact(), function (MutableField $mutable) {
                $mutable->compactify([
                    CompactRules\CompactFieldGroup::class,
                ]);
            })
            ->modify('fields', function ($fields) {
                return $this->packFields($fields);
            })
            ->toArray();
    }

    /**
     * Pack given fields definition
     *
     * @param array $array
     * @return array
     */
    protected function packFields($array)
    {
        return (new MutableFieldCollection($array))
            ->associateBy('name', function ($item) {
                return str_replace('-', '_', sanitize_title_with_dashes($item['type'] . '-' . $item['label']));
            })
            ->transformAsMutable(function (MutableField $item) {
                return $item
                    ->modifyAsMutable(function (MutableField $fieldConfig) {
                        switch ($fieldConfig->get('type')) {
                            case 'repeater':
                            case 'group':
                                $fieldConfig
                                    ->modify('sub_fields', function ($subFields) {
                                        return $this->packFields($subFields);
                                    });
                                break;
                            case 'flexible_content':
                            $fieldConfig['layouts'] = (new MutableFieldCollection($fieldConfig['layouts']))
                                ->associateBy('name')
                                ->transformAsMutable(function (MutableField $layoutConfig) {
                                    return $layoutConfig
                                        ->shiftToLast('sub_fields')
                                        ->modify('sub_fields', function ($layoutSubFields) {
                                            return $this->packFields($layoutSubFields);
                                        });
                                });
                                break;
                            default:
                                break;
                        }
                        $fieldConfig->when($this->isCompact(), function (MutableField $mutable) {
                            $mutable->compactify($this->getFieldCompactRules($mutable->toArray()));
                        });
                        return $fieldConfig;
                    })
                    ->toArray();
            })
            ->toArray();
    }

    /**
     * Retrieve available compacting rules based on given field array
     *
     * @param array $array
     * @return array
     */
    protected function getFieldCompactRules($array)
    {
        $rules = [
            CompactRules\CompactField::class,
        ];
        $typeRules = Arr::get($this->compactRulesByType, Arr::get($array, 'type'), []);
        if (!is_array($typeRules)) {
            $typeRules = [$typeRules];
        }
        if (count($typeRules) > 0) {
            $rules = array_merge($rules, $typeRules);
        }
        return $rules;
    }
}
