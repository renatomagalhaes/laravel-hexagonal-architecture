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

    /**
     * Teste: Deve retornar mensagens de erro personalizadas
     */
    public function test_should_return_custom_error_messages(): void
    {
        // Arrange
        $request = new CreateProductRequest();
        $messages = $request->messages();

        // Act & Assert
        $this->assertIsArray($messages);
        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('name.string', $messages);
        $this->assertArrayHasKey('name.max', $messages);
        $this->assertArrayHasKey('name.min', $messages);
        $this->assertArrayHasKey('name.regex', $messages);
        $this->assertArrayHasKey('price.required', $messages);
        $this->assertArrayHasKey('price.numeric', $messages);
        $this->assertArrayHasKey('price.min', $messages);
        $this->assertArrayHasKey('category_id.required', $messages);
        $this->assertArrayHasKey('category_id.string', $messages);
        $this->assertArrayHasKey('category_id.min', $messages);
        $this->assertArrayHasKey('description.string', $messages);
        $this->assertArrayHasKey('description.max', $messages);

        // Verificar se as mensagens estão em português
        $this->assertEquals('O nome do produto é obrigatório.', $messages['name.required']);
        $this->assertEquals('O nome do produto deve ser um texto.', $messages['name.string']);
        $this->assertEquals('O nome do produto não pode ter mais de 255 caracteres.', $messages['name.max']);
        $this->assertEquals('O nome do produto deve ter pelo menos 1 caractere.', $messages['name.min']);
        $this->assertEquals('O nome do produto não pode começar ou terminar com espaços.', $messages['name.regex']);
        $this->assertEquals('O preço do produto é obrigatório.', $messages['price.required']);
        $this->assertEquals('O preço do produto deve ser um número.', $messages['price.numeric']);
        $this->assertEquals('O preço do produto não pode ser negativo.', $messages['price.min']);
        $this->assertEquals('O ID da categoria é obrigatório.', $messages['category_id.required']);
        $this->assertEquals('O ID da categoria deve ser um texto.', $messages['category_id.string']);
        $this->assertEquals('O ID da categoria deve ter pelo menos 1 caractere.', $messages['category_id.min']);
        $this->assertEquals('A descrição deve ser um texto.', $messages['description.string']);
        $this->assertEquals('A descrição não pode ter mais de 1000 caracteres.', $messages['description.max']);
    }

    /**
     * Teste: Deve retornar atributos personalizados
     */
    public function test_should_return_custom_attributes(): void
    {
        // Arrange
        $request = new CreateProductRequest();
        $attributes = $request->attributes();

        // Act & Assert
        $this->assertIsArray($attributes);
        $this->assertArrayHasKey('name', $attributes);
        $this->assertArrayHasKey('price', $attributes);
        $this->assertArrayHasKey('category_id', $attributes);
        $this->assertArrayHasKey('description', $attributes);

        // Verificar se os atributos estão em português
        $this->assertEquals('nome do produto', $attributes['name']);
        $this->assertEquals('preço', $attributes['price']);
        $this->assertEquals('ID da categoria', $attributes['category_id']);
        $this->assertEquals('descrição', $attributes['description']);
    }

    /**
     * Teste: Deve autorizar requisição
     */
    public function test_should_authorize_request(): void
    {
        // Arrange
        $request = new CreateProductRequest();

        // Act & Assert
        $this->assertTrue($request->authorize());
    }
}
