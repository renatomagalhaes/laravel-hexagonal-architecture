<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\UseCases\FindProductsByCategoryUseCase;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case FindProductsByCategoryUseCase seguindo TDD
 * 
 * Use Cases orquestram a lógica de negócio e coordenam
 * as operações entre diferentes camadas da aplicação.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class FindProductsByCategoryUseCaseTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private FindProductsByCategoryUseCase $findProductsByCategoryUseCase;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->findProductsByCategoryUseCase = new FindProductsByCategoryUseCase($this->productRepository);
    }

    /**
     * Teste: Deve retornar produtos de uma categoria específica
     */
    public function test_should_return_products_for_specific_category(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $products = [
            new Product('Produto Categoria 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
            new Product('Produto Categoria 1', 200.00, $categoryId, 'Descrição 2', 'product_2'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $result = $this->findProductsByCategoryUseCase->execute($categoryId);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
        
        foreach ($result as $product) {
            $this->assertEquals($categoryId, $product->getCategoryId());
        }
    }

    /**
     * Teste: Deve retornar lista vazia quando não existem produtos na categoria
     */
    public function test_should_return_empty_list_when_no_products_in_category(): void
    {
        // Arrange
        $categoryId = 'category_inexistente';

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn([]);

        // Act
        $result = $this->findProductsByCategoryUseCase->execute($categoryId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Teste: Deve chamar o repositório para buscar produtos por categoria
     */
    public function test_should_call_repository_to_find_products_by_category(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $products = [
            new Product('Produto Categoria 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $this->findProductsByCategoryUseCase->execute($categoryId);

        // Assert - As expectativas do mock são verificadas automaticamente
    }

    /**
     * Teste: Deve retornar apenas produtos da categoria especificada
     */
    public function test_should_return_only_products_from_specified_category(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $products = [
            new Product('Produto Categoria 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
            new Product('Produto Categoria 1', 200.00, $categoryId, 'Descrição 2', 'product_2'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $result = $this->findProductsByCategoryUseCase->execute($categoryId);

        // Assert
        $this->assertCount(2, $result);
        
        $this->assertEquals('Produto Categoria 1', $result[0]->getName());
        $this->assertEquals($categoryId, $result[0]->getCategoryId());
        
        $this->assertEquals('Produto Categoria 1', $result[1]->getName());
        $this->assertEquals($categoryId, $result[1]->getCategoryId());
    }

    /**
     * Teste: Deve funcionar com diferentes IDs de categoria
     */
    public function test_should_work_with_different_category_ids(): void
    {
        // Arrange
        $categoryId1 = 'category_1';
        $categoryId2 = 'category_2';
        
        $products1 = [
            new Product('Produto Categoria 1', 100.00, $categoryId1, 'Descrição 1', 'product_1'),
        ];
        
        $products2 = [
            new Product('Produto Categoria 2', 200.00, $categoryId2, 'Descrição 2', 'product_2'),
        ];

        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findByCategoryId')
            ->willReturnMap([
                [$categoryId1, $products1],
                [$categoryId2, $products2],
            ]);

        // Act
        $result1 = $this->findProductsByCategoryUseCase->execute($categoryId1);
        $result2 = $this->findProductsByCategoryUseCase->execute($categoryId2);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertEquals($categoryId1, $result1[0]->getCategoryId());
        
        $this->assertCount(1, $result2);
        $this->assertEquals($categoryId2, $result2[0]->getCategoryId());
    }
}
