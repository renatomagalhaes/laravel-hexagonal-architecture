<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\DTOs\CreateProductDTO;
use App\Core\Application\UseCases\CreateProductUseCase;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case CreateProduct seguindo TDD
 * 
 * Este teste define o comportamento esperado do Use Case CreateProduct
 * antes de implementá-lo, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class CreateProductUseCaseTest extends TestCase
{
    private CreateProductUseCase $useCase;
    private ProductRepository|MockObject $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock do repositório
        $this->productRepository = $this->createMock(ProductRepository::class);
        
        // Instância do Use Case com dependência injetada
        $this->useCase = new CreateProductUseCase($this->productRepository);
    }

    /**
     * Teste: Deve criar um produto com dados válidos
     */
    public function test_should_create_product_with_valid_data(): void
    {
        // Arrange
        $dto = new CreateProductDTO(
            'Smartphone Samsung Galaxy',
            1299.99,
            'category_1',
            'Smartphone com tela de 6.1 polegadas'
        );

        $expectedProduct = new Product(
            $dto->name,
            $dto->price,
            $dto->categoryId,
            $dto->description
        );

        // Mock do repositório
        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Product::class))
            ->willReturn($expectedProduct);

        // Act
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($dto->name, $result->getName());
        $this->assertEquals($dto->price, $result->getPrice());
        $this->assertEquals($dto->categoryId, $result->getCategoryId());
        $this->assertEquals($dto->description, $result->getDescription());
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $dto = new CreateProductDTO(
            '', // Nome vazio
            1299.99,
            'category_1',
            'Descrição válida'
        );

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');

        $this->useCase->execute($dto);
    }

    /**
     * Teste: Deve lançar exceção quando preço for negativo
     */
    public function test_should_throw_exception_when_price_is_negative(): void
    {
        // Arrange
        $dto = new CreateProductDTO(
            'Produto válido',
            -100.00, // Preço negativo
            'category_1',
            'Descrição válida'
        );

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative');

        $this->useCase->execute($dto);
    }

    /**
     * Teste: Deve chamar o repositório para salvar o produto
     */
    public function test_should_call_repository_to_save_product(): void
    {
        // Arrange
        $dto = new CreateProductDTO(
            'Produto Teste',
            100.00,
            'category_1',
            'Descrição teste'
        );

        // Mock do repositório
        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Product $product) use ($dto) {
                return $product->getName() === $dto->name &&
                       $product->getPrice() === $dto->price &&
                       $product->getCategoryId() === $dto->categoryId &&
                       $product->getDescription() === $dto->description;
            }))
            ->willReturn(new Product($dto->name, $dto->price, $dto->categoryId, $dto->description));

        // Act
        $this->useCase->execute($dto);

        // Assert - O mock já verifica se o método foi chamado corretamente
        $this->assertTrue(true);
    }

    /**
     * Teste: Deve retornar o produto salvo pelo repositório
     */
    public function test_should_return_product_saved_by_repository(): void
    {
        // Arrange
        $dto = new CreateProductDTO(
            'Produto Teste',
            100.00,
            'category_1',
            'Descrição teste'
        );

        $savedProduct = new Product(
            $dto->name,
            $dto->price,
            $dto->categoryId,
            $dto->description,
            'product_123' // ID gerado pelo repositório
        );

        // Mock do repositório
        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($savedProduct);

        // Act
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertSame($savedProduct, $result);
        $this->assertEquals('product_123', $result->getId());
    }
}
