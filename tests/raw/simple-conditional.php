<?php
return [
    'key' => 'group_acbdefg',
    'title' => 'Sample Field',
    'fields' => [
        array(
            'key' => 'field_123456',
            'label' => 'Show Other Field',
            'name' => 'show_other_field',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '30',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        array(
            'key' => 'field_654321',
            'label' => 'Other Field',
            'name' => 'other_field',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'wrapper' => array(
                'width' => '60',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_123456',
                        'operator' => '==',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_nested_12345',
            'label' => 'Nested Group',
            'name' => 'nested_group',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_nested_sub_123456',
                    'label' => 'Show Other Field',
                    'name' => 'show_other_field',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '30',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 0,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_nested_sub_654321',
                    'label' => 'Other Field',
                    'name' => 'other_field',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '60',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_nested_sub_123456',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
            )
        )
    ]
];
