<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\UseCases\DeleteProductUseCase;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case DeleteProductUseCase seguindo TDD
 * 
 * Use Cases orquestram a lógica de negócio e coordenam
 * as operações entre diferentes camadas da aplicação.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class DeleteProductUseCaseTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private DeleteProductUseCase $deleteProductUseCase;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->deleteProductUseCase = new DeleteProductUseCase($this->productRepository);
    }

    /**
     * Teste: Deve deletar um produto existente
     */
    public function test_should_delete_existing_product(): void
    {
        // Arrange
        $productId = 'product_123';
        $existingProduct = new Product(
            'Produto para deletar',
            100.00,
            'category_1',
            'Descrição do produto',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('delete')
            ->with($productId)
            ->willReturn(true);

        // Act
        $result = $this->deleteProductUseCase->execute($productId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve lançar exceção quando produto não for encontrado
     */
    public function test_should_throw_exception_when_product_not_found(): void
    {
        // Arrange
        $productId = 'product_inexistente';

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product not found');

        $this->deleteProductUseCase->execute($productId);
    }

    /**
     * Teste: Deve chamar o repositório para verificar se produto existe
     */
    public function test_should_call_repository_to_check_product_exists(): void
    {
        // Arrange
        $productId = 'product_123';
        $existingProduct = new Product(
            'Produto para deletar',
            100.00,
            'category_1',
            'Descrição do produto',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('delete')
            ->with($productId)
            ->willReturn(true);

        // Act
        $this->deleteProductUseCase->execute($productId);

        // Assert - As expectativas do mock são verificadas automaticamente
    }

    /**
     * Teste: Deve chamar o repositório para deletar o produto
     */
    public function test_should_call_repository_to_delete_product(): void
    {
        // Arrange
        $productId = 'product_123';
        $existingProduct = new Product(
            'Produto para deletar',
            100.00,
            'category_1',
            'Descrição do produto',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('delete')
            ->with($productId)
            ->willReturn(true);

        // Act
        $this->deleteProductUseCase->execute($productId);

        // Assert - As expectativas do mock são verificadas automaticamente
    }

    /**
     * Teste: Deve retornar false quando repositório retorna false
     */
    public function test_should_return_false_when_repository_returns_false(): void
    {
        // Arrange
        $productId = 'product_123';
        $existingProduct = new Product(
            'Produto para deletar',
            100.00,
            'category_1',
            'Descrição do produto',
            $productId
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('delete')
            ->with($productId)
            ->willReturn(false);

        // Act
        $result = $this->deleteProductUseCase->execute($productId);

        // Assert
        $this->assertFalse($result);
    }
}
