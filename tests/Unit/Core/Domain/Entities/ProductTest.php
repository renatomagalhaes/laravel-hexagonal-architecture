<?php

namespace Tests\Unit\Core\Domain\Entities;

use App\Core\Domain\Entities\Product;
use PHPUnit\Framework\TestCase;

/**
 * Teste da entidade Product seguindo TDD
 * 
 * Este teste define o comportamento esperado da entidade Product
 * antes de implementá-la, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Domain\Entities
 */
class ProductTest extends TestCase
{
    /**
     * Teste: Deve criar um produto com dados válidos
     * 
     * Este é o primeiro teste que define o comportamento básico
     * esperado da entidade Product.
     */
    public function test_should_create_product_with_valid_data(): void
    {
        // Arrange - Preparar os dados de teste
        $name = 'Smartphone Samsung Galaxy';
        $price = 1299.99;
        $categoryId = 1;
        $description = 'Smartphone com tela de 6.1 polegadas';

        // Act - Executar a ação (criar o produto)
        $product = new Product($name, $price, $categoryId, $description);

        // Assert - Verificar se o comportamento está correto
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEquals($categoryId, $product->getCategoryId());
        $this->assertEquals($description, $product->getDescription());
        $this->assertNotNull($product->getId());
        $this->assertInstanceOf(\DateTime::class, $product->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $product->getUpdatedAt());
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     * 
     * Testa as regras de negócio da entidade Product.
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $emptyName = '';
        $price = 1299.99;
        $categoryId = 1;
        $description = 'Descrição válida';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');

        new Product($emptyName, $price, $categoryId, $description);
    }

    /**
     * Teste: Deve lançar exceção quando preço for negativo
     * 
     * Testa as regras de negócio relacionadas ao preço.
     */
    public function test_should_throw_exception_when_price_is_negative(): void
    {
        // Arrange
        $name = 'Produto válido';
        $negativePrice = -100.00;
        $categoryId = 1;
        $description = 'Descrição válida';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product price cannot be negative');

        new Product($name, $negativePrice, $categoryId, $description);
    }

    /**
     * Teste: Deve permitir atualizar o nome do produto
     * 
     * Testa o comportamento de atualização da entidade.
     */
    public function test_should_allow_updating_product_name(): void
    {
        // Arrange
        $product = new Product('Nome Original', 100.00, 1, 'Descrição');
        $newName = 'Nome Atualizado';

        // Act
        $product->updateName($newName);

        // Assert
        $this->assertEquals($newName, $product->getName());
        $this->assertGreaterThan($product->getCreatedAt(), $product->getUpdatedAt());
    }

    /**
     * Teste: Deve permitir atualizar o preço do produto
     * 
     * Testa o comportamento de atualização do preço.
     */
    public function test_should_allow_updating_product_price(): void
    {
        // Arrange
        $product = new Product('Produto', 100.00, 1, 'Descrição');
        $newPrice = 150.00;

        // Act
        $product->updatePrice($newPrice);

        // Assert
        $this->assertEquals($newPrice, $product->getPrice());
        $this->assertGreaterThan($product->getCreatedAt(), $product->getUpdatedAt());
    }

    /**
     * Teste: Deve lançar exceção ao tentar atualizar nome para vazio
     * 
     * Testa as validações nas operações de atualização.
     */
    public function test_should_throw_exception_when_updating_name_to_empty(): void
    {
        // Arrange
        $product = new Product('Nome Válido', 100.00, 1, 'Descrição');

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');

        $product->updateName('');
    }
}
