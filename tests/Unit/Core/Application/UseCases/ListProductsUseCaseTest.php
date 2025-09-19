<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\UseCases\ListProductsUseCase;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case ListProductsUseCase seguindo TDD
 * 
 * Use Cases orquestram a lógica de negócio e coordenam
 * as operações entre diferentes camadas da aplicação.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class ListProductsUseCaseTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private ListProductsUseCase $listProductsUseCase;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->listProductsUseCase = new ListProductsUseCase($this->productRepository);
    }

    /**
     * Teste: Deve retornar lista de produtos quando existem produtos
     */
    public function test_should_return_products_list_when_products_exist(): void
    {
        // Arrange
        $products = [
            new Product('Produto 1', 100.00, 'category_1', 'Descrição 1', 'product_1'),
            new Product('Produto 2', 200.00, 'category_2', 'Descrição 2', 'product_2'),
            new Product('Produto 3', 300.00, 'category_1', 'Descrição 3', 'product_3'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($products);

        // Act
        $result = $this->listProductsUseCase->execute();

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(Product::class, $result);
        
        $this->assertEquals('Produto 1', $result[0]->getName());
        $this->assertEquals('Produto 2', $result[1]->getName());
        $this->assertEquals('Produto 3', $result[2]->getName());
    }

    /**
     * Teste: Deve retornar lista vazia quando não existem produtos
     */
    public function test_should_return_empty_list_when_no_products_exist(): void
    {
        // Arrange
        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $result = $this->listProductsUseCase->execute();

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Teste: Deve chamar o repositório para buscar todos os produtos
     */
    public function test_should_call_repository_to_find_all_products(): void
    {
        // Arrange
        $products = [
            new Product('Produto 1', 100.00, 'category_1', 'Descrição 1', 'product_1'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($products);

        // Act
        $this->listProductsUseCase->execute();

        // Assert - As expectativas do mock são verificadas automaticamente
    }

    /**
     * Teste: Deve retornar os produtos na ordem retornada pelo repositório
     */
    public function test_should_return_products_in_repository_order(): void
    {
        // Arrange
        $products = [
            new Product('Produto C', 300.00, 'category_3', 'Descrição C', 'product_3'),
            new Product('Produto A', 100.00, 'category_1', 'Descrição A', 'product_1'),
            new Product('Produto B', 200.00, 'category_2', 'Descrição B', 'product_2'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($products);

        // Act
        $result = $this->listProductsUseCase->execute();

        // Assert
        $this->assertCount(3, $result);
        $this->assertEquals('Produto C', $result[0]->getName());
        $this->assertEquals('Produto A', $result[1]->getName());
        $this->assertEquals('Produto B', $result[2]->getName());
    }
}
