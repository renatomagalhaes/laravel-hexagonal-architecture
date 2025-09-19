<?php

namespace Tests\Unit\Http\Controllers;

use App\Core\Application\DTOs\CreateCategoryDTO;
use App\Core\Application\UseCases\CreateCategoryUseCase;
use App\Core\Domain\Entities\Category;
use App\Http\Controllers\CategoryController;
use App\Http\Requests\CreateCategoryRequest;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Controller CategoryController seguindo TDD
 * 
 * Controllers HTTP são responsáveis por receber requisições HTTP,
 * validar dados de entrada e orquestrar os Use Cases correspondentes.
 * 
 * @package Tests\Unit\Http\Controllers
 */
class CategoryControllerTest extends TestCase
{
    private CreateCategoryUseCase|MockObject $createCategoryUseCase;
    private CategoryController $categoryController;

    protected function setUp(): void
    {
        $this->createCategoryUseCase = $this->createMock(CreateCategoryUseCase::class);
        $this->categoryController = new CategoryController($this->createCategoryUseCase);
    }

    /**
     * Teste: Deve criar uma categoria com dados válidos
     */
    public function test_should_create_category_with_valid_data(): void
    {
        // Arrange
        $requestData = [
            'name' => 'Eletrônicos',
            'description' => 'Categoria para produtos eletrônicos'
        ];

        $request = new CreateCategoryRequest();
        $request->merge($requestData);
        $expectedCategory = new Category(
            $requestData['name'],
            $requestData['description'],
            'category_123'
        );

        $this->createCategoryUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (CreateCategoryDTO $dto) use ($requestData) {
                return $dto->name === $requestData['name'] &&
                       $dto->description === $requestData['description'];
            }))
            ->willReturn($expectedCategory);

        // Act
        $response = $this->categoryController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('category_123', $responseData['data']['id']);
        $this->assertEquals($requestData['name'], $responseData['data']['name']);
        $this->assertEquals($requestData['description'], $responseData['data']['description']);
        $this->assertTrue($responseData['data']['is_active']);
    }

    /**
     * Teste: Deve retornar erro 400 quando dados são inválidos
     */
    public function test_should_return_400_when_data_is_invalid(): void
    {
        // Arrange
        $requestData = [
            'name' => '', // Nome vazio
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $request->merge($requestData);

        $this->createCategoryUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \InvalidArgumentException('Category name cannot be empty'));

        // Act
        $response = $this->categoryController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Category name cannot be empty', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica
     */
    public function test_should_return_500_when_generic_exception(): void
    {
        // Arrange
        $requestData = [
            'name' => 'Categoria Teste',
            'description' => 'Descrição válida'
        ];

        $request = new CreateCategoryRequest();
        $request->merge($requestData);

        $this->createCategoryUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->categoryController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Database connection failed', $responseData['message']);
    }
}
