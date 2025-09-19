<?php

namespace Tests\Unit\Core\Infrastructure\Database;

use App\Core\Domain\Entities\Product;
use App\Core\Infrastructure\Database\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

/**
 * Teste da implementação InMemoryProductRepository seguindo TDD
 * 
 * Este teste define o comportamento esperado da implementação
 * do repositório em memória, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Infrastructure\Database
 */
class InMemoryProductRepositoryTest extends TestCase
{
    private InMemoryProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryProductRepository();
    }

    /**
     * Teste: Deve salvar um produto e retorná-lo com ID
     */
    public function test_should_save_product_and_return_with_id(): void
    {
        // Arrange
        $product = new Product(
            'Smartphone Samsung Galaxy',
            1299.99,
            1,
            'Smartphone com tela de 6.1 polegadas'
        );

        // Act
        $savedProduct = $this->repository->save($product);

        // Assert
        $this->assertInstanceOf(Product::class, $savedProduct);
        $this->assertNotEmpty($savedProduct->getId());
        $this->assertEquals($product->getName(), $savedProduct->getName());
        $this->assertEquals($product->getPrice(), $savedProduct->getPrice());
        $this->assertEquals($product->getCategoryId(), $savedProduct->getCategoryId());
        $this->assertEquals($product->getDescription(), $savedProduct->getDescription());
    }

    /**
     * Teste: Deve encontrar um produto pelo ID
     */
    public function test_should_find_product_by_id(): void
    {
        // Arrange
        $product = new Product('Produto Teste', 100.00, 1, 'Descrição teste');
        $savedProduct = $this->repository->save($product);

        // Act
        $foundProduct = $this->repository->findById($savedProduct->getId());

        // Assert
        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($savedProduct->getId(), $foundProduct->getId());
        $this->assertEquals($savedProduct->getName(), $foundProduct->getName());
    }

    /**
     * Teste: Deve retornar null quando produto não for encontrado
     */
    public function test_should_return_null_when_product_not_found(): void
    {
        // Arrange
        $nonExistentId = 'non_existent_id';

        // Act
        $result = $this->repository->findById($nonExistentId);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Teste: Deve retornar todos os produtos salvos
     */
    public function test_should_return_all_saved_products(): void
    {
        // Arrange
        $product1 = new Product('Produto 1', 100.00, 1, 'Descrição 1');
        $product2 = new Product('Produto 2', 200.00, 2, 'Descrição 2');
        
        $this->repository->save($product1);
        $this->repository->save($product2);

        // Act
        $allProducts = $this->repository->findAll();

        // Assert
        $this->assertIsArray($allProducts);
        $this->assertCount(2, $allProducts);
        $this->assertContainsOnlyInstancesOf(Product::class, $allProducts);
    }

    /**
     * Teste: Deve deletar um produto pelo ID
     */
    public function test_should_delete_product_by_id(): void
    {
        // Arrange
        $product = new Product('Produto para deletar', 100.00, 1, 'Descrição');
        $savedProduct = $this->repository->save($product);

        // Act
        $result = $this->repository->delete($savedProduct->getId());

        // Assert
        $this->assertTrue($result);
        $this->assertNull($this->repository->findById($savedProduct->getId()));
    }

    /**
     * Teste: Deve retornar false ao tentar deletar produto inexistente
     */
    public function test_should_return_false_when_deleting_non_existent_product(): void
    {
        // Arrange
        $nonExistentId = 'non_existent_id';

        // Act
        $result = $this->repository->delete($nonExistentId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve encontrar produtos por categoria
     */
    public function test_should_find_products_by_category_id(): void
    {
        // Arrange
        $product1 = new Product('Produto Categoria 1', 100.00, 'category_1', 'Descrição 1');
        $product2 = new Product('Produto Categoria 1', 200.00, 'category_1', 'Descrição 2');
        $product3 = new Product('Produto Categoria 2', 300.00, 'category_2', 'Descrição 3');
        
        $this->repository->save($product1);
        $this->repository->save($product2);
        $this->repository->save($product3);

        // Act
        $productsCategory1 = $this->repository->findByCategoryId('category_1');
        $productsCategory2 = $this->repository->findByCategoryId('category_2');

        // Assert
        $this->assertCount(2, $productsCategory1);
        $this->assertCount(1, $productsCategory2);
        
        foreach ($productsCategory1 as $product) {
            $this->assertEquals('category_1', $product->getCategoryId());
        }
        
        foreach ($productsCategory2 as $product) {
            $this->assertEquals('category_2', $product->getCategoryId());
        }
    }

    /**
     * Teste: Deve atualizar um produto existente quando já tem ID
     */
    public function test_should_update_existing_product_when_it_has_id(): void
    {
        // Arrange
        $originalProduct = new Product('Produto Original', 100.00, 1, 'Descrição original');
        $savedProduct = $this->repository->save($originalProduct);
        
        // Modifica o produto salvo
        $savedProduct->updateName('Produto Atualizado');
        $savedProduct->updatePrice(150.00);

        // Act
        $updatedProduct = $this->repository->save($savedProduct);

        // Assert
        $this->assertSame($savedProduct, $updatedProduct);
        $this->assertEquals('Produto Atualizado', $updatedProduct->getName());
        $this->assertEquals(150.00, $updatedProduct->getPrice());
        
        // Verifica se foi realmente atualizado no repositório
        $foundProduct = $this->repository->findById($savedProduct->getId());
        $this->assertEquals('Produto Atualizado', $foundProduct->getName());
        $this->assertEquals(150.00, $foundProduct->getPrice());
    }

    /**
     * Teste: Deve manter o mesmo ID ao atualizar um produto existente
     */
    public function test_should_maintain_same_id_when_updating_existing_product(): void
    {
        // Arrange
        $originalProduct = new Product('Produto Original', 100.00, 1, 'Descrição original');
        $savedProduct = $this->repository->save($originalProduct);
        $originalId = $savedProduct->getId();
        
        // Modifica o produto
        $savedProduct->updateName('Produto Modificado');

        // Act
        $updatedProduct = $this->repository->save($savedProduct);

        // Assert
        $this->assertEquals($originalId, $updatedProduct->getId());
        $this->assertCount(1, $this->repository->findAll());
    }

    /**
     * Teste: Deve criar produto com ID específico quando fornecido
     */
    public function test_should_create_product_with_specific_id_when_provided(): void
    {
        // Arrange
        $specificId = 'custom_product_123';
        $product = new Product('Produto Custom', 100.00, 1, 'Descrição', $specificId);

        // Act
        $savedProduct = $this->repository->save($product);

        // Assert
        $this->assertEquals($specificId, $savedProduct->getId());
        $this->assertSame($product, $savedProduct);
        
        // Verifica se foi salvo corretamente
        $foundProduct = $this->repository->findById($specificId);
        $this->assertSame($product, $foundProduct);
    }

    /**
     * Teste: Deve gerar IDs únicos para produtos sem ID
     */
    public function test_should_generate_unique_ids_for_products_without_id(): void
    {
        // Arrange
        $product1 = new Product('Produto 1', 100.00, 1, 'Descrição 1');
        $product2 = new Product('Produto 2', 200.00, 1, 'Descrição 2');
        $product3 = new Product('Produto 3', 300.00, 1, 'Descrição 3');

        // Act
        $saved1 = $this->repository->save($product1);
        $saved2 = $this->repository->save($product2);
        $saved3 = $this->repository->save($product3);

        // Assert
        $this->assertNotEmpty($saved1->getId());
        $this->assertNotEmpty($saved2->getId());
        $this->assertNotEmpty($saved3->getId());
        
        $this->assertNotEquals($saved1->getId(), $saved2->getId());
        $this->assertNotEquals($saved2->getId(), $saved3->getId());
        $this->assertNotEquals($saved1->getId(), $saved3->getId());
        
        // Verifica se os IDs começam com 'product_'
        $this->assertStringStartsWith('product_', $saved1->getId());
        $this->assertStringStartsWith('product_', $saved2->getId());
        $this->assertStringStartsWith('product_', $saved3->getId());
    }
}
