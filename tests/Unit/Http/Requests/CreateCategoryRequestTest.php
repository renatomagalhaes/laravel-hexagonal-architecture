<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CreateCategoryRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Teste do Form Request CreateCategoryRequest seguindo TDD
 * 
 * Form Requests são responsáveis por validar dados de entrada
 * antes que cheguem aos controllers e use cases.
 * 
 * @package Tests\Unit\Http\Requests
 */
class CreateCategoryRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste: Deve validar dados válidos
     */
    public function test_should_validate_valid_data(): void
    {
        // Arrange
        $data = [
            'name' => 'Eletrônicos',
            'description' => 'Categoria para produtos eletrônicos'
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Teste: Deve falhar quando nome está vazio
     */
    public function test_should_fail_when_name_is_empty(): void
    {
        // Arrange
        $data = [
            'name' => '',
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando nome é muito longo
     */
    public function test_should_fail_when_name_is_too_long(): void
    {
        // Arrange
        $data = [
            'name' => str_repeat('A', 256), // 256 caracteres
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando nome contém apenas espaços
     */
    public function test_should_fail_when_name_is_only_spaces(): void
    {
        // Arrange
        $data = [
            'name' => '   ',
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve aceitar descrição vazia (nullable)
     */
    public function test_should_accept_empty_description(): void
    {
        // Arrange
        $data = [
            'name' => 'Categoria Válida',
            'description' => ''
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Teste: Deve falhar quando descrição é muito longa
     */
    public function test_should_fail_when_description_is_too_long(): void
    {
        // Arrange
        $data = [
            'name' => 'Categoria Válida',
            'description' => str_repeat('A', 1001) // 1001 caracteres
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando nome não é string
     */
    public function test_should_fail_when_name_is_not_string(): void
    {
        // Arrange
        $data = [
            'name' => 123,
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando descrição não é string
     */
    public function test_should_fail_when_description_is_not_string(): void
    {
        // Arrange
        $data = [
            'name' => 'Categoria Válida',
            'description' => 123
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve aceitar descrição opcional
     */
    public function test_should_accept_optional_description(): void
    {
        // Arrange
        $data = [
            'name' => 'Categoria Válida'
            // description não fornecida
        ];

        $request = new CreateCategoryRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }
}
