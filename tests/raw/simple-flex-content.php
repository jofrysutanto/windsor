<?php
return [
    'key' => 'group_acbdefg',
    'title' => 'Sample Field',
    'fields' => [
        [
            'key' => 'field_5f1bec5775a4d',
            'label' => 'Flexible Content',
            'name' => 'flexible_content',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array(
                'layout_5f1bec603b6fe' => array(
                    'key' => 'layout_5f1bec603b6fe',
                    'name' => 'call_to_action',
                    'label' => 'Call to Action',
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5f1bec6c75a4e',
                            'label' => 'CTA Heading',
                            'name' => 'cta_heading',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5f1bec7575a4f',
                            'label' => 'CTA Link',
                            'name' => 'cta_link',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                'layout_5f1bec7e75a50' => array(
                    'key' => 'layout_5f1bec7e75a50',
                    'name' => 'slider',
                    'label' => 'Slider',
                    'display' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5f1bec8275a51',
                            'label' => 'Slider Items',
                            'name' => 'slider_items',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => '',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_5f1bec8975a52',
                                    'label' => 'Slider Heading',
                                    'name' => 'slider_heading',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                ),
                                array(
                                    'key' => 'field_5f1bec9475a53',
                                    'label' => 'Slider Image',
                                    'name' => 'slider_image',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'return_format' => 'array',
                                    'preview_size' => 'medium',
                                    'library' => 'all',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'min_size' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                ),
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'button_label' => 'Add Row',
            'min' => '',
            'max' => '',
        ]
    ]
];
