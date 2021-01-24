<?php

namespace Phu1237\LaravelSettings\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use Phu1237\LaravelSettings\Models\Setting;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAll()
    {
        $response = settings()->all();
        $this->assertInstanceOf(Collection::class, $response);
    }

    public function testCreate()
    {
        $key = 'test 1';
        $meta = [
            'type' => 'text',
            'placeholder' => 'test placeholder',
            'required' => true
        ];
        // $meta = '{"type":"text","placeholder":"test placeholder","required":true}';
        $response = settings()->store($key, 'test value', $meta);
        $this->assertEquals(Setting::find($key), $response);
    }

    /**
     * settings()->get($key)
     * settings($key)
     */
    public function testGetItem()
    {
        // Expected result: Setting
        $key = 'test 1';
        $expected = Setting::find($key);
        $this->assertEquals($expected, settings()->get($key));
        $this->assertEquals($expected, settings($key));
        // Expected result: null
        $key = 'fake test';
        $this->assertNull(settings()->get($key));
        $this->assertNull(settings($key));
    }

    /**
     * settings()->value($key)
     */
    public function testGetItemValue()
    {
        // Expected result: string
        $key = 'test 1';
        $expected = Setting::find($key)->value;
        $actual = settings()->value($key);
        $this->assertEquals($expected, $actual);
    }

    /**
     * settings->set($key, $value)
     * settings->set($array)
     * settings->value($key, $value)
     * settings->value($array)
     */
    public function testSetItemValue()
    {
        $expected = 'test value';
        // Testing settings->set($key, $value)
        settings()->set('test 2', $expected);
        $actual = Setting::find('test 2')->value;
        $this->assertEquals($expected, $actual);
        // Testing settings->set($array)
        settings()->set(['test 3' => $expected]);
        $actual = Setting::find('test 3')->value;
        $this->assertEquals($expected, $actual);
        // Testing settings->value($key, $value)
        $expected = 'new value 1';
        settings()->value('test 1', $expected);
        $actual = Setting::find('test 1')->value;
        $this->assertEquals($expected, $actual);
        // Testing settings->value($array);
        $expected = 'new value 2';
        settings()->value(['test 1' => $expected]);
        $actual = Setting::find('test 1')->value;
        $this->assertEquals($expected, $actual);
    }

    public function testGetCachedValueAfterSet()
    {
        $key = 'test 1';
        $expected = 'new test item cache value';
        settings()->value($key, $expected);
        $actual = settings()->value($key);
        $this->assertEquals($expected, $actual);
    }

    public function testGetMeta()
    {
        $key = 'test 1';
        $this->assertEquals(Setting::find($key)->meta, settings()->meta($key));
    }

    public function testGetMetaAttribute()
    {
        $key = 'test 1';
        // Expected result: string
        $attribute = 'type';
        $expected = Setting::find($key)->meta->$attribute;
        $actual = settings()->meta($key, $attribute);
        $this->assertEquals($expected, $actual);
        // Expected result: null
        $attribute = 'fake type';
        $actual = settings()->meta($key, $attribute);
        $this->assertNull($actual);
    }

    public function testSetMetaAttribute()
    {
        $key = 'test 1';
        // Testing settings()->meta($key, $array);
        $expected = 'new test placeholder';
        settings()->meta($key, ['placeholder' => $expected]);
        $actual = Setting::find($key)->meta->placeholder;
        $this->assertEquals($expected, $actual);
        // Testing settings()->meta($key, $attribute, $value)
        $expected = 'new test placeholder 2';
        settings()->meta($key, 'placeholder', $expected);
        $actual = Setting::find($key)->meta->placeholder;
        $this->assertEquals($expected, $actual);
    }

    public function testGetCachedMetaAfterSet()
    {
        $key = 'test 1';
        $expected = 'new test item cache placeholder';
        settings()->meta($key, 'placeholder', $expected);
        $actual = settings()->meta($key, 'placeholder');
        $this->assertEquals($expected, $actual);
    }

    /**
     * settings()->has($key)
     */
    public function testHas()
    {
        // Expect result: true
        $key = 'test 1';
        $response = settings()->has($key);
        $this->assertTrue($response);
        // Expect result: false
        $key = 'fake test';
        $response = settings()->has($key);
        $this->assertFalse($response);
    }
}
