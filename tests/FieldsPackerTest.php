<?php

use Windsor\Admin\Exporter\FieldsPacker;

final class FieldsPackerTest extends BaseTestCase
{
    public function testSimpleKey()
    {
        $fields = require 'raw/default.php';
        $packer = new FieldsPacker($fields);
        $result = $packer->pack();
        $this->assertTrue(isset($result['fields']['my_new_field']));
    }

    public function testGroup()
    {
        $fields = require 'raw/simple-group.php';
        $packer = new FieldsPacker($fields);
        $result = $packer->pack();
        $this->assertTrue(isset($result['fields']['group']));
        $this->assertEquals([
            'my_text', 'my_text_2'
        ], array_keys($result['fields']['group']['sub_fields']));
    }

    public function testRepeater()
    {
        $fields = require 'raw/simple-repeater.php';
        $packer = new FieldsPacker($fields);
        $result = $packer->pack();
        $this->assertTrue(isset($result['fields']['repeats']));
        $this->assertEquals([
            'my_text', 'my_textarea'
        ], array_keys($result['fields']['repeats']['sub_fields']));
    }

    public function testFlexibleContent()
    {
        $fields = require 'raw/simple-flex-content.php';
        $packer = new FieldsPacker($fields);
        $result = $packer->pack();
        $this->assertTrue(isset($result['fields']['flexible_content']));
        $this->assertEquals([
            'call_to_action', 'slider'
        ], array_keys($result['fields']['flexible_content']['layouts']));
        $this->assertEquals([
            'cta_heading', 'cta_link'
        ], array_keys($result['fields']['flexible_content']['layouts']['call_to_action']['sub_fields']));
    }

    public function testConditionRule()
    {
        $fields = require 'raw/simple-conditional.php';
        $packer = new FieldsPacker($fields);
        $result = $packer->pack();
        $this->assertEquals(
            ['field' => '~field_123456', 'operator' => '==', 'value' => '1'],
            $result['fields']['other_field']['conditional_logic'][0][0]
        );
        $nestedGroupFields =  $result['fields']['nested_group']['sub_fields'];
        $this->assertEquals(
            ['field' => '~field_nested_sub_123456', 'operator' => '==', 'value' => '1'],
            $nestedGroupFields['other_field']['conditional_logic'][0][0]
        );
    }
}
