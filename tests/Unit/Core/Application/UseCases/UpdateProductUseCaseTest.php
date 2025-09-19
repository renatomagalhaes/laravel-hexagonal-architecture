<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\DTOs\UpdateProductDTO;
use App\Core\Application\UseCases\UpdateProductUseCase;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case UpdateProductUseCase seguindo TDD
 * 
 * Use Cases orquestram a lógica de negócio e coordenam
 * as operações entre diferentes camadas da aplicação.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class UpdateProductUseCaseTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private UpdateProductUseCase $updateProductUseCase;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->updateProductUseCase = new UpdateProductUseCase($this->productRepository);
    }

    /**
     * Teste: Deve atualizar um produto com dados válidos
     */
    public function test_should_update_product_with_valid_data(): void
    {
        // Arrange
        $productId = 'product_123';
        $dto = new UpdateProductDTO(
            $productId,
            'Smartphone Samsung Galaxy Atualizado',
            1399.99,
            'category_2',
            'Smartphone atualizado com tela de 6.1 polegadas'
        );

        $existingProduct = new Product(
            'Produto Original',
            1299.99,
            'category_1',
            'Descrição original',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Product $product) {
                return $product;
            });

        // Act
        $result = $this->updateProductUseCase->execute($dto);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($dto->name, $result->getName());
        $this->assertEquals($dto->price, $result->getPrice());
        $this->assertEquals($dto->categoryId, $result->getCategoryId());
        $this->assertEquals($dto->description, $result->getDescription());
    }

    /**
     * Teste: Deve lançar exceção quando produto não for encontrado
     */
    public function test_should_throw_exception_when_product_not_found(): void
    {
        // Arrange
        $productId = 'product_inexistente';
        $dto = new UpdateProductDTO(
            $productId,
            'Nome Atualizado',
            100.00,
            'category_1',
            'Descrição atualizada'
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product not found');

        $this->updateProductUseCase->execute($dto);
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $productId = 'product_123';
        $dto = new UpdateProductDTO(
            $productId,
            '', // Nome vazio
            100.00,
            'category_1',
            'Descrição válida'
        );

        $existingProduct = new Product(
            'Produto Original',
            100.00,
            'category_1',
            'Descrição original',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');

        $this->updateProductUseCase->execute($dto);
    }

    /**
     * Teste: Deve lançar exceção quando preço for negativo
     */
    public function test_should_throw_exception_when_price_is_negative(): void
    {
        // Arrange
        $productId = 'product_123';
        $dto = new UpdateProductDTO(
            $productId,
            'Nome válido',
            -100.00, // Preço negativo
            'category_1',
            'Descrição válida'
        );

        $existingProduct = new Product(
            'Produto Original',
            100.00,
            'category_1',
            'Descrição original',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative');

        $this->updateProductUseCase->execute($dto);
    }

    /**
     * Teste: Deve chamar o repositório para salvar o produto atualizado
     */
    public function test_should_call_repository_to_save_updated_product(): void
    {
        // Arrange
        $productId = 'product_123';
        $dto = new UpdateProductDTO(
            $productId,
            'Nome Atualizado',
            100.00,
            'category_1',
            'Descrição atualizada'
        );

        $existingProduct = new Product(
            'Produto Original',
            100.00,
            'category_1',
            'Descrição original',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Product $product) use ($dto) {
                return $product->getId() === $dto->id &&
                       $product->getName() === $dto->name &&
                       $product->getPrice() === $dto->price &&
                       $product->getCategoryId() === $dto->categoryId &&
                       $product->getDescription() === $dto->description;
            }))
            ->willReturn($existingProduct);

        // Act
        $this->updateProductUseCase->execute($dto);

        // Assert - As expectativas do mock são verificadas automaticamente
    }

    /**
     * Teste: Deve retornar o produto atualizado salvo pelo repositório
     */
    public function test_should_return_updated_product_saved_by_repository(): void
    {
        // Arrange
        $productId = 'product_123';
        $dto = new UpdateProductDTO(
            $productId,
            'Nome Atualizado',
            100.00,
            'category_1',
            'Descrição atualizada'
        );

        $existingProduct = new Product(
            'Produto Original',
            100.00,
            'category_1',
            'Descrição original',
            $productId
        );

        $updatedProduct = new Product(
            $dto->name,
            $dto->price,
            $dto->categoryId,
            $dto->description,
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($updatedProduct);

        // Act
        $result = $this->updateProductUseCase->execute($dto);

        // Assert
        $this->assertSame($updatedProduct, $result);
    }
}
