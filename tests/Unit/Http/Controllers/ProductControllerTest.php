<?php

namespace Tests\Unit\Http\Controllers;

use App\Core\Application\DTOs\CreateProductDTO;
use App\Core\Application\DTOs\UpdateProductDTO;
use App\Core\Application\UseCases\CreateProductUseCase;
use App\Core\Application\UseCases\DeleteProductUseCase;
use App\Core\Application\UseCases\FindProductsByCategoryUseCase;
use App\Core\Application\UseCases\ListProductsUseCase;
use App\Core\Application\UseCases\UpdateProductUseCase;
use App\Core\Domain\Entities\Product;
use App\Http\Controllers\ProductController;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Controller ProductController seguindo TDD
 * 
 * Controllers HTTP são responsáveis por receber requisições HTTP,
 * validar dados de entrada e orquestrar os Use Cases correspondentes.
 * 
 * @package Tests\Unit\Http\Controllers
 */
class ProductControllerTest extends TestCase
{
    private CreateProductUseCase|MockObject $createProductUseCase;
    private UpdateProductUseCase|MockObject $updateProductUseCase;
    private DeleteProductUseCase|MockObject $deleteProductUseCase;
    private ListProductsUseCase|MockObject $listProductsUseCase;
    private FindProductsByCategoryUseCase|MockObject $findProductsByCategoryUseCase;
    private ProductController $productController;

    protected function setUp(): void
    {
        $this->createProductUseCase = $this->createMock(CreateProductUseCase::class);
        $this->updateProductUseCase = $this->createMock(UpdateProductUseCase::class);
        $this->deleteProductUseCase = $this->createMock(DeleteProductUseCase::class);
        $this->listProductsUseCase = $this->createMock(ListProductsUseCase::class);
        $this->findProductsByCategoryUseCase = $this->createMock(FindProductsByCategoryUseCase::class);

        $this->productController = new ProductController(
            $this->createProductUseCase,
            $this->updateProductUseCase,
            $this->deleteProductUseCase,
            $this->listProductsUseCase,
            $this->findProductsByCategoryUseCase
        );
    }

    /**
     * Teste: Deve criar um produto com dados válidos
     */
    public function test_should_create_product_with_valid_data(): void
    {
        // Arrange
        $requestData = [
            'name' => 'Smartphone Samsung Galaxy',
            'price' => 1299.99,
            'category_id' => 'category_1',
            'description' => 'Smartphone com tela de 6.1 polegadas'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);
        $expectedProduct = new Product(
            $requestData['name'],
            $requestData['price'],
            $requestData['category_id'],
            $requestData['description'],
            'product_123'
        );

        $this->createProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (CreateProductDTO $dto) use ($requestData) {
                return $dto->name === $requestData['name'] &&
                       $dto->price === $requestData['price'] &&
                       $dto->categoryId === $requestData['category_id'] &&
                       $dto->description === $requestData['description'];
            }))
            ->willReturn($expectedProduct);

        // Act
        $response = $this->productController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('product_123', $responseData['data']['id']);
        $this->assertEquals($requestData['name'], $responseData['data']['name']);
        $this->assertEquals($requestData['price'], $responseData['data']['price']);
        $this->assertEquals($requestData['category_id'], $responseData['data']['category_id']);
        $this->assertEquals($requestData['description'], $responseData['data']['description']);
    }

    /**
     * Teste: Deve retornar erro 400 quando dados são inválidos
     */
    public function test_should_return_400_when_data_is_invalid(): void
    {
        // Arrange
        $requestData = [
            'name' => '', // Nome vazio
            'price' => -100.00, // Preço negativo
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);

        $this->createProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \InvalidArgumentException('Product name cannot be empty'));

        // Act
        $response = $this->productController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Product name cannot be empty', $responseData['message']);
    }

    /**
     * Teste: Deve listar todos os produtos
     */
    public function test_should_list_all_products(): void
    {
        // Arrange
        $products = [
            new Product('Produto 1', 100.00, 'category_1', 'Descrição 1', 'product_1'),
            new Product('Produto 2', 200.00, 'category_2', 'Descrição 2', 'product_2'),
        ];

        $this->listProductsUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn($products);

        // Act
        $response = $this->productController->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertCount(2, $responseData['data']);
        $this->assertEquals('product_1', $responseData['data'][0]['id']);
        $this->assertEquals('product_2', $responseData['data'][1]['id']);
    }

    /**
     * Teste: Deve atualizar um produto existente
     */
    public function test_should_update_existing_product(): void
    {
        // Arrange
        $productId = 'product_123';
        $requestData = [
            'name' => 'Smartphone Atualizado',
            'price' => 1399.99,
            'category_id' => 'category_2',
            'description' => 'Smartphone atualizado'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);
        $updatedProduct = new Product(
            $requestData['name'],
            $requestData['price'],
            $requestData['category_id'],
            $requestData['description'],
            $productId
        );

        $this->updateProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (UpdateProductDTO $dto) use ($productId, $requestData) {
                return $dto->id === $productId &&
                       $dto->name === $requestData['name'] &&
                       $dto->price === $requestData['price'] &&
                       $dto->categoryId === $requestData['category_id'] &&
                       $dto->description === $requestData['description'];
            }))
            ->willReturn($updatedProduct);

        // Act
        $response = $this->productController->update($request, $productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals($productId, $responseData['data']['id']);
        $this->assertEquals($requestData['name'], $responseData['data']['name']);
    }

    /**
     * Teste: Deve retornar erro 404 quando produto não existe para atualização
     */
    public function test_should_return_404_when_product_not_found_for_update(): void
    {
        // Arrange
        $productId = 'product_inexistente';
        $requestData = [
            'name' => 'Produto Atualizado',
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição atualizada'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);

        $this->updateProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \InvalidArgumentException('Product not found'));

        // Act
        $response = $this->productController->update($request, $productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Product not found', $responseData['message']);
    }

    /**
     * Teste: Deve deletar um produto existente
     */
    public function test_should_delete_existing_product(): void
    {
        // Arrange
        $productId = 'product_123';

        $this->deleteProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($productId)
            ->willReturn(true);

        // Act
        $response = $this->productController->destroy($productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertEquals('Product deleted successfully', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 404 quando produto não existe para exclusão
     */
    public function test_should_return_404_when_product_not_found_for_deletion(): void
    {
        // Arrange
        $productId = 'product_inexistente';

        $this->deleteProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($productId)
            ->willThrowException(new \InvalidArgumentException('Product not found'));

        // Act
        $response = $this->productController->destroy($productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Product not found', $responseData['message']);
    }

    /**
     * Teste: Deve buscar produtos por categoria
     */
    public function test_should_find_products_by_category(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $products = [
            new Product('Produto Categoria 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
            new Product('Produto Categoria 1', 200.00, $categoryId, 'Descrição 2', 'product_2'),
        ];

        $this->findProductsByCategoryUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $response = $this->productController->findByCategory($categoryId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('success', $responseData['status']);
        $this->assertCount(2, $responseData['data']);
        $this->assertEquals($categoryId, $responseData['data'][0]['category_id']);
        $this->assertEquals($categoryId, $responseData['data'][1]['category_id']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica na listagem
     */
    public function test_should_return_500_when_generic_exception_in_list(): void
    {
        // Arrange
        $this->listProductsUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->productController->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Database connection failed', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica na criação
     */
    public function test_should_return_500_when_generic_exception_in_creation(): void
    {
        // Arrange
        $requestData = [
            'name' => 'Produto Teste',
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição válida'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);

        $this->createProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->productController->store($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Internal server error', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica na atualização
     */
    public function test_should_return_500_when_generic_exception_in_update(): void
    {
        // Arrange
        $productId = 'product_123';
        $requestData = [
            'name' => 'Produto Atualizado',
            'price' => 100.00,
            'category_id' => 'category_1',
            'description' => 'Descrição atualizada'
        ];

        $request = new CreateProductRequest();
        $request->merge($requestData);

        $this->updateProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->productController->update($request, $productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Internal server error', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica na exclusão
     */
    public function test_should_return_500_when_generic_exception_in_deletion(): void
    {
        // Arrange
        $productId = 'product_123';

        $this->deleteProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($productId)
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->productController->destroy($productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Internal server error', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando ocorre exceção genérica na busca por categoria
     */
    public function test_should_return_500_when_generic_exception_in_find_by_category(): void
    {
        // Arrange
        $categoryId = 'category_1';

        $this->findProductsByCategoryUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($categoryId)
            ->willThrowException(new \Exception('Database connection failed'));

        // Act
        $response = $this->productController->findByCategory($categoryId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Database connection failed', $responseData['message']);
    }

    /**
     * Teste: Deve retornar erro 500 quando falha ao deletar produto
     */
    public function test_should_return_500_when_deletion_fails(): void
    {
        // Arrange
        $productId = 'product_123';

        $this->deleteProductUseCase
            ->expects($this->once())
            ->method('execute')
            ->with($productId)
            ->willReturn(false);

        // Act
        $response = $this->productController->destroy($productId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('Failed to delete product', $responseData['message']);
    }
}