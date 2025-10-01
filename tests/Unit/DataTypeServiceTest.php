<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Unit;

use Carbon\Carbon;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;

class DataTypeServiceTest extends TestCase
{
    /** @test */
    public function it_can_cast_string_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('123', 'string');

        $this->assertIsString($result);
        $this->assertEquals('123', $result);
    }

    /** @test */
    public function it_can_cast_integer_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('123', 'integer');

        $this->assertIsInt($result);
        $this->assertEquals(123, $result);
    }

    /** @test */
    public function it_can_cast_float_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('123.45', 'float');

        $this->assertIsFloat($result);
        $this->assertEquals(123.45, $result);
    }

    /** @test */
    public function it_can_cast_boolean_values()
    {
        $service = new DataTypeService;

        $trueValues = ['1', 'true', 'on', 'yes'];
        $falseValues = ['0', 'false', 'off', 'no'];

        foreach ($trueValues as $value) {
            $result = $service->castForUse($value, 'boolean');
            $this->assertIsBool($result);
            $this->assertTrue($result);
        }

        foreach ($falseValues as $value) {
            $result = $service->castForUse($value, 'boolean');
            $this->assertIsBool($result);
            $this->assertFalse($result);
        }
    }

    /** @test */
    public function it_can_cast_array_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('one,two,three', 'array');

        $this->assertIsArray($result);
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /** @test */
    public function it_can_cast_json_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('{"name":"John","age":30}', 'json');

        $this->assertIsArray($result);
        $this->assertEquals(['name' => 'John', 'age' => 30], $result);
    }

    /** @test */
    public function it_can_cast_date_values()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('2023-10-20', 'date');

        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals('2023-10-20', $result->format('Y-m-d'));
    }

    /** @test */
    public function it_can_get_validation_rules()
    {
        $service = new DataTypeService;

        $this->assertEquals('required|string', $service->getValidationRule('string'));
        $this->assertEquals('required|integer', $service->getValidationRule('integer'));
        $this->assertEquals('required|numeric', $service->getValidationRule('float'));
        $this->assertEquals('required|string|in:1,true,on,yes,0,false,off,no', $service->getValidationRule('boolean'));
        $this->assertEquals('required|json', $service->getValidationRule('json'));
    }

    /** @test */
    public function it_can_get_types_for_select()
    {
        $service = new DataTypeService;

        $types = $service->getTypesForSelect();

        $this->assertIsArray($types);
        $this->assertArrayHasKey('string', $types);
        $this->assertArrayHasKey('integer', $types);
        $this->assertArrayHasKey('float', $types);
        $this->assertArrayHasKey('boolean', $types);
        $this->assertArrayHasKey('array', $types);
        $this->assertArrayHasKey('json', $types);
    }

    /** @test */
    public function it_returns_original_value_for_unknown_type()
    {
        $service = new DataTypeService;

        $result = $service->castForUse('test', 'unknown_type');

        $this->assertEquals('test', $result);
    }
}
