<?php

use Windsor\Admin\Exporter\FieldsPacker;

final class CompactRulesTest extends BaseTestCase
{
    public function testDefaultFieldRule()
    {
        $fields = require 'raw/default.php';
        $packer = new FieldsPacker($fields);
        $result = $packer
            ->setMode('compact')
            ->pack();
        $this->assertEquals([
            "key" => "group_acbdefg",
            "title" => "Sample Field",
            "fields" => [
                "my_new_field" => [
                    "key" => "field_5f0ff95bb56b8",
                    "label" => "My new field",
                    "type" => "text",
                ]
            ]
        ], $result);
    }
}
