<?php
namespace Windsor\Admin\Exporter;

use Tightenco\Collect\Support\Arr;

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

    /**
     * List of instantiable classes to simplify fields configuration
     *
     * @var array
     */
    protected $compactRulesByType = [
        // Basic
        'number'           => CompactRules\CompactNumber::class,
        'range'            => CompactRules\CompactRange::class,
        'textarea'         => CompactRules\CompactTextArea::class,
        // Content
        'image'            => CompactRules\CompactImage::class,
        'file'             => CompactRules\CompactFile::class,
        'wysiwyg'          => CompactRules\CompactRichEditor::class,
        'oembed'           => CompactRules\CompactOEmbed::class,
        'true_false'       => CompactRules\CompactTrueFalse::class,
        'gallery'          => CompactRules\CompactGallery::class,
        // Choice
        'select'           => CompactRules\CompactSelect::class,
        'checkbox'         => CompactRules\CompactCheckbox::class,
        'radio'            => CompactRules\CompactRadio::class,
        'button_group'     => CompactRules\CompactButtonGroup::class,
        // Relational
        'post_object'      => CompactRules\CompactPostObject::class,
        'page_link'        => CompactRules\CompactPageLink::class,
        'relationship'     => CompactRules\CompactRelationship::class,
        'taxonomy'         => CompactRules\CompactTaxonomy::class,
        'user'             => CompactRules\CompactUser::class,
        // jQuery
        'google_map'       => CompactRules\CompactGoogleMap::class,
        // Layout
        'tab'              => CompactRules\CompactTab::class,
        'accordion'        => CompactRules\CompactAccordion::class,
        'message'          => CompactRules\CompactMessage::class,
        'clone'            => CompactRules\CompactClone::class,
        'repeater'         => CompactRules\CompactRepeater::class,
        'flexible_content' => CompactRules\CompactFlexibleContent::class,
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
                    ->modify(function ($fieldArray) {
                        return $this->expandConditionalLogic($fieldArray);
                    })
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
                                $fieldConfig->shiftToLast('layouts');
                                $fieldConfig['layouts'] = (new MutableFieldCollection($fieldConfig['layouts']))
                                    ->associateBy('name')
                                    ->transformAsMutable(function (MutableField $layoutConfig) {
                                        return $layoutConfig
                                            ->shiftToLast('sub_fields')
                                            ->modify('sub_fields', function ($layoutSubFields) {
                                                return $this->packFields($layoutSubFields);
                                            })
                                            ->when($this->isCompact(), function (MutableField $mutable) {
                                                $mutable->compactify([
                                                    CompactRules\CompactLayout::class
                                                ]);
                                            })
                                            ->toArray();
                                    })
                                    ->toArray();
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

    /**
     * Prefix all conditional logic field to use expanded keys
     *
     * @param array $array
     * @return array
     */
    protected function expandConditionalLogic($array)
    {
        if (!Arr::has($array, 'conditional_logic')) {
            return $array;
        }
        $conditions = Arr::get($array, 'conditional_logic');
        if (!is_array($conditions)) {
            return $array;
        }
        if (count($conditions) <= 0) {
            return $array;
        }
        foreach ($conditions as $and => $andContent) {
            foreach ($andContent as $or => $value) {
                Arr::set(
                    $array,
                    sprintf('conditional_logic.%s.%s.field', $and, $or),
                    '~' . Arr::get($value, 'field')
                );
            }
        }
        return $array;
    }
}
