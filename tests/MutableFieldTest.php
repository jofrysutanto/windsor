<?php

use Windsor\Admin\Exporter\MutableField;

final class MutableFieldTest extends BaseTestCase
{
    public function testShiftToLast()
    {
        $field = new MutableField([
            'shouldBeLast' => 'foo',
            'other' => 'bar',
        ]);
        $field->shiftToLast('shouldBeLast');
        $result = $field->toArray();
        $last = array_pop($result);
        $this->assertEquals('foo', $last);
    }

    public function testWhenTrue()
    {
        $field = new MutableField([
            'foo' => 'bar',
        ]);
        $field->when(true, function ($field) {
            $field['foo'] = 'bar2';
        });
        $this->assertEquals(['foo' => 'bar2'], $field->toArray());
    }

    public function testWhenFalse()
    {
        $field = new MutableField([
            'foo' => 'bar',
        ]);
        $field->when(false, function ($field) {
            $field['foo'] = 'bar2';
        });
        $this->assertEquals(['foo' => 'bar'], $field->toArray());
    }

    public function testModifyMutable()
    {
        $field = new MutableField([
            'foo' => [
                'bar' => 'alpha'
            ],
        ]);
        $field->modifyAsMutable('foo', function ($sub) {
            $this->assertTrue($sub instanceof MutableField);
            $sub['bar'] = 'omega';
            return $sub;
        });
        $this->assertEquals('omega', $field->toArray()['foo']['bar']);
        $field->modifyAsMutable(function ($sub) {
            $sub['baz'] = 'baz2';
            return $sub;
        });
        $this->assertEquals('baz2', $field->toArray()['baz']);
    }

    public function testModify()
    {
        $field = new MutableField([
            'foo' => [
                'bar' => 'alpha'
            ],
        ]);
        $field->modifyAsMutable('foo', function ($sub) {
            $sub['bar'] = 'omega';
            return $sub;
        });
        $this->assertEquals('omega', $field->toArray()['foo']['bar']);
        $field->modifyAsMutable(function ($sub) {
            $sub['baz'] = 'baz2';
            return $sub;
        });
        $this->assertEquals('baz2', $field->toArray()['baz']);
    }
}
