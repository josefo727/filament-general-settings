<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Josefo727\FilamentGeneralSettings\Models\GeneralSetting;
use Josefo727\FilamentGeneralSettings\Services\DataTypeService;
use Josefo727\FilamentGeneralSettings\Tests\TestCase;

class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_string_field_correctly()
    {
        // Obtener las reglas de validación para el tipo string
        $rules = GeneralSetting::getValidationRules('string');

        // Validar un string normal
        $validator = Validator::make([
            'name' => 'test_string',
            'type' => 'string',
            'value' => 'This is a valid string',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un string válido no debería fallar la validación');

        // Validar un número en formato string (debe ser válido)
        $validator = Validator::make([
            'name' => 'test_string_with_number',
            'type' => 'string',
            'value' => '12345',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un número en formato string debería ser válido');
    }

    /** @test */
    public function it_validates_integer_field_correctly()
    {
        // Obtener las reglas de validación para el tipo integer
        $rules = GeneralSetting::getValidationRules('integer');

        // Validar un entero válido
        $validator = Validator::make([
            'name' => 'test_integer',
            'type' => 'integer',
            'value' => 12345,
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un entero válido no debería fallar la validación');

        // Validar un valor inválido (string)
        $validator = Validator::make([
            'name' => 'test_invalid_integer',
            'type' => 'integer',
            'value' => 'not an integer',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un string no debería ser válido como entero');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_float_field_correctly()
    {
        // Obtener las reglas de validación para el tipo float
        $rules = GeneralSetting::getValidationRules('float');

        // Validar un float válido
        $validator = Validator::make([
            'name' => 'test_float',
            'type' => 'float',
            'value' => 123.45,
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un float válido no debería fallar la validación');

        // Validar un valor inválido (string)
        $validator = Validator::make([
            'name' => 'test_invalid_float',
            'type' => 'float',
            'value' => 'not a float',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un string no debería ser válido como float');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_json_field_correctly()
    {
        // Obtener las reglas de validación para el tipo json
        $rules = GeneralSetting::getValidationRules('json');

        // Validar un JSON válido
        $validator = Validator::make([
            'name' => 'test_json',
            'type' => 'json',
            'value' => '{"key":"value"}',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un JSON válido no debería fallar la validación');

        // Validar un JSON inválido
        $validator = Validator::make([
            'name' => 'test_invalid_json',
            'type' => 'json',
            'value' => '{key:value}', // Faltan comillas
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un JSON inválido debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_email_field_correctly()
    {
        // Obtener las reglas de validación para el tipo email
        $rules = GeneralSetting::getValidationRules('email');

        // Validar un email válido
        $validator = Validator::make([
            'name' => 'test_email',
            'type' => 'email',
            'value' => 'test@example.com',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un email válido no debería fallar la validación');

        // Validar un email inválido
        $validator = Validator::make([
            'name' => 'test_invalid_email',
            'type' => 'email',
            'value' => 'not-an-email',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un email inválido debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_emails_field_correctly()
    {
        // Obtener las reglas de validación para el tipo emails
        $rules = GeneralSetting::getValidationRules('emails');

        // Validar emails válidos como string (formato correcto)
        $validator = Validator::make([
            'name' => 'test_emails',
            'type' => 'emails',
            'value' => 'test1@example.com,test2@example.com',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Emails válidos no deberían fallar la validación');

        // Validar emails inválidos (uno inválido en el string)
        $validator = Validator::make([
            'name' => 'test_invalid_emails',
            'type' => 'emails',
            'value' => 'test1@example.com,not-an-email',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un string con un email inválido debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_url_field_correctly()
    {
        // Obtener las reglas de validación para el tipo url
        $rules = GeneralSetting::getValidationRules('url');

        // Validar una URL válida
        $validator = Validator::make([
            'name' => 'test_url',
            'type' => 'url',
            'value' => 'https://example.com',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Una URL válida no debería fallar la validación');

        // Validar una URL inválida
        $validator = Validator::make([
            'name' => 'test_invalid_url',
            'type' => 'url',
            'value' => 'not-a-url',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Una URL inválida debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_date_field_correctly()
    {
        // Obtener las reglas de validación para el tipo date
        $rules = GeneralSetting::getValidationRules('date');

        // Validar una fecha válida
        $validator = Validator::make([
            'name' => 'test_date',
            'type' => 'date',
            'value' => '2023-10-20',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Una fecha válida no debería fallar la validación');

        // Validar una fecha inválida
        $validator = Validator::make([
            'name' => 'test_invalid_date',
            'type' => 'date',
            'value' => 'not-a-date',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Una fecha inválida debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_array_field_correctly()
    {
        // Obtener las reglas de validación para el tipo array
        $rules = GeneralSetting::getValidationRules('array');

        // Validar un string con formato de array (separado por comas)
        $validator = Validator::make([
            'name' => 'test_array',
            'type' => 'array',
            'value' => 'item1,item2,item3',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un string con formato de array válido no debería fallar la validación');

        // Validar un string vacío (inválido para array)
        $validator = Validator::make([
            'name' => 'test_empty_array',
            'type' => 'array',
            'value' => '',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un string vacío debería fallar la validación para array');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_edit_form_correctly()
    {
        // Crear un setting primero
        $setting = GeneralSetting::create([
            'name' => 'test_edit',
            'type' => 'string',
            'value' => 'original value',
            'description' => 'Test description',
        ]);

        // Obtener las reglas de validación para una edición válida
        $rules = GeneralSetting::getValidationRules('string', $setting->id);

        // Validar una edición válida
        $validator = Validator::make([
            'name' => 'test_edit',
            'type' => 'string',
            'value' => 'updated value',
            'description' => 'Updated description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Una edición válida no debería fallar la validación');

        // Obtener las reglas para validar un cambio de tipo con valor inválido
        $rules = GeneralSetting::getValidationRules('integer', $setting->id);

        // Validar una edición inválida (tipo cambiado a integer con un valor string)
        $validator = Validator::make([
            'name' => 'test_edit',
            'type' => 'integer',
            'value' => 'not an integer',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un valor string no debería ser válido para un campo integer');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_uses_data_type_service_validation_rules()
    {
        $dataTypeService = new DataTypeService;

        // Obtener todos los tipos
        $types = $dataTypeService->getTypes();

        foreach ($types as $type => $config) {
            // Saltar tipos que se prueban por separado
            if (in_array($type, ['string', 'integer', 'float', 'json', 'email', 'emails', 'url', 'date', 'array'])) {
                continue;
            }

            // Obtener un valor válido para este tipo
            $value = $this->getValidValueForType($type);

            // Obtener las reglas de validación para este tipo
            $rules = GeneralSetting::getValidationRules($type);

            // Validar con un valor válido
            $validator = Validator::make([
                'name' => 'test_'.$type,
                'type' => $type,
                'value' => $value,
                'description' => 'Test '.$type,
            ], $rules);

            $this->assertFalse($validator->fails(), "La validación para el tipo '$type' con valor '$value' no debería fallar");

            // Crear un setting con este tipo
            $setting = GeneralSetting::create([
                'name' => 'test_'.$type,
                'type' => $type,
                'value' => $value,
                'description' => 'Test '.$type,
            ]);

            // Verificar que se creó correctamente
            $this->assertDatabaseHas(\Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(), [
                'name' => 'test_'.$type,
                'type' => $type,
            ]);
        }
    }

    /** @test */
    public function it_validates_password_min_length()
    {
        // Obtener las reglas de validación para el tipo password
        $rules = GeneralSetting::getValidationRules('password');

        // Validar una contraseña válida (min 4 caracteres)
        $validator = Validator::make([
            'name' => 'test_password',
            'type' => 'password',
            'value' => 'pass1234',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Una contraseña válida no debería fallar la validación');

        // Validar una contraseña inválida (menos de 4 caracteres)
        $validator = Validator::make([
            'name' => 'test_invalid_password',
            'type' => 'password',
            'value' => 'abc',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Una contraseña con menos de 4 caracteres debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_time_format()
    {
        // Obtener las reglas de validación para el tipo time
        $rules = GeneralSetting::getValidationRules('time');

        // Validar un formato de hora válido (H:i:s)
        $validator = Validator::make([
            'name' => 'test_time_format',
            'type' => 'time',
            'value' => '14:30:45',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un formato de hora válido no debería fallar la validación');

        // Validar un formato de hora inválido
        $validator = Validator::make([
            'name' => 'test_invalid_time_format',
            'type' => 'time',
            'value' => '14:30', // Faltan los segundos
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un formato de hora sin segundos debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_datetime_format()
    {
        // Obtener las reglas de validación para el tipo datetime
        $rules = GeneralSetting::getValidationRules('datetime');

        // Validar un formato de fecha y hora válido (Y-m-d H:i:s)
        $validator = Validator::make([
            'name' => 'test_datetime_format',
            'type' => 'datetime',
            'value' => '2023-10-20 14:30:45',
            'description' => 'Test description',
        ], $rules);

        $this->assertFalse($validator->fails(), 'Un formato de fecha y hora válido no debería fallar la validación');

        // Validar un formato de fecha y hora inválido
        $validator = Validator::make([
            'name' => 'test_invalid_datetime_format',
            'type' => 'datetime',
            'value' => '2023-10-20 14:30', // Faltan los segundos
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un formato de fecha y hora sin segundos debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');
    }

    /** @test */
    public function it_validates_boolean_field()
    {
        // Obtener las reglas de validación para el tipo boolean
        $rules = GeneralSetting::getValidationRules('boolean');

        // Validar valores booleanos válidos (como strings)
        $validValues = ['1', 'true', 'on', 'yes', '0', 'false', 'off', 'no'];

        foreach ($validValues as $value) {
            $validator = Validator::make([
                'name' => 'test_boolean',
                'type' => 'boolean',
                'value' => $value,
                'description' => 'Test description',
            ], $rules);

            $this->assertFalse($validator->fails(), "El valor booleano '$value' no debería fallar la validación");
        }

        // Validar un valor inválido
        $validator = Validator::make([
            'name' => 'test_invalid_boolean',
            'type' => 'boolean',
            'value' => 'invalid_boolean',
            'description' => 'Test description',
        ], $rules);

        $this->assertTrue($validator->fails(), 'Un valor no booleano debería fallar la validación');
        $this->assertTrue($validator->errors()->has('value'), 'Debería tener un error en el campo value');

        // Crear un setting con un valor booleano válido
        $setting = GeneralSetting::create([
            'name' => 'test_boolean_valid',
            'type' => 'boolean',
            'value' => 'true',
            'description' => 'Test description',
        ]);

        $this->assertDatabaseHas(\Josefo727\FilamentGeneralSettings\FilamentGeneralSettingsServiceProvider::getTableName(), [
            'name' => 'test_boolean_valid',
            'type' => 'boolean',
        ]);

        // Verificar que el valor se convierte a booleano
        $this->assertIsBool(GeneralSetting::getValue('test_boolean_valid'));
        $this->assertTrue(GeneralSetting::getValue('test_boolean_valid'));
    }

    /**
     * Helper method to get a valid value for each type
     */
    private function getValidValueForType(string $type)
    {
        switch ($type) {
            case 'boolean':
                return 'true';
            case 'time':
                return '12:30:00';
            case 'datetime':
                return '2023-10-20 12:30:00';
            case 'password':
                return 'password123';
            default:
                return 'default_value';
        }
    }
}
