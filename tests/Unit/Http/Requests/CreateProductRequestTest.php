<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CreateProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Teste do Form Request CreateProductRequest seguindo TDD
 * 
 * Form Requests são responsáveis por validar dados de entrada
 * antes que cheguem aos controllers e use cases.
 * 
 * @package Tests\Unit\Http\Requests
 */
class CreateProductRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste: Deve validar dados válidos
     */
    public function test_should_validate_valid_data(): void
    {
        // Arrange
        $data = [
            'name' => 'Smartphone Samsung Galaxy',
            'price' => 1299.99,
            'category_id' => 'category_1',
            'description' => 'Smartphone com tela de 6.1 polegadas'
        ];

        $request = new CreateProductRequest();
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
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
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
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
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
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando preço é negativo
     */
    public function test_should_fail_when_price_is_negative(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Válido',
            'price' => -100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('price', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve falhar quando preço não é numérico
     */
    public function test_should_fail_when_price_is_not_numeric(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Válido',
            'price' => 'preço inválido',
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('price', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve aceitar preço zero
     */
    public function test_should_accept_zero_price(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Grátis',
            'price' => 0.00,
            'category_id' => 'category_1',
            'description' => 'Produto gratuito'
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Teste: Deve falhar quando category_id está vazio
     */
    public function test_should_fail_when_category_id_is_empty(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Válido',
            'price' => 100.00,
            'category_id' => '',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('category_id', $validator->errors()->toArray());
    }

    /**
     * Teste: Deve aceitar descrição vazia (nullable)
     */
    public function test_should_accept_empty_description(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Válido',
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => ''
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }

    /**
     * Teste: Deve aceitar descrição opcional
     */
    public function test_should_accept_optional_description(): void
    {
        // Arrange
        $data = [
            'name' => 'Produto Válido',
            'price' => 100.00,
            'category_id' => 'category_1'
            // description não fornecida
        ];

        $request = new CreateProductRequest();
        $validator = Validator::make($data, $request->rules());

        // Act & Assert
        $this->assertTrue($validator->passes());
        $this->assertEmpty($validator->errors()->all());
    }
}
