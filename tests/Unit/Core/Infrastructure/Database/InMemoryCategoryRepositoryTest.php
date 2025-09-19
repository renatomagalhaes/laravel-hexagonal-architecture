<?php

namespace Tests\Unit\Core\Infrastructure\Database;

use App\Core\Domain\Entities\Category;
use App\Core\Infrastructure\Database\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

/**
 * Teste da implementação InMemoryCategoryRepository seguindo TDD
 * 
 * Este teste define o comportamento esperado da implementação
 * do repositório em memória, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Infrastructure\Database
 */
class InMemoryCategoryRepositoryTest extends TestCase
{
    private InMemoryCategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryCategoryRepository();
    }

    /**
     * Teste: Deve salvar uma categoria e retorná-la com ID
     */
    public function test_should_save_category_and_return_with_id(): void
    {
        // Arrange
        $category = new Category(
            'Eletrônicos',
            'Produtos eletrônicos e tecnológicos'
        );

        // Act
        $savedCategory = $this->repository->save($category);

        // Assert
        $this->assertInstanceOf(Category::class, $savedCategory);
        $this->assertNotEmpty($savedCategory->getId());
        $this->assertEquals($category->getName(), $savedCategory->getName());
        $this->assertEquals($category->getDescription(), $savedCategory->getDescription());
        $this->assertEquals($category->isActive(), $savedCategory->isActive());
    }

    /**
     * Teste: Deve encontrar uma categoria pelo ID
     */
    public function test_should_find_category_by_id(): void
    {
        // Arrange
        $category = new Category('Categoria Teste', 'Descrição teste');
        $savedCategory = $this->repository->save($category);

        // Act
        $foundCategory = $this->repository->findById($savedCategory->getId());

        // Assert
        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals($savedCategory->getId(), $foundCategory->getId());
        $this->assertEquals($savedCategory->getName(), $foundCategory->getName());
    }

    /**
     * Teste: Deve retornar null quando categoria não for encontrada
     */
    public function test_should_return_null_when_category_not_found(): void
    {
        // Arrange
        $nonExistentId = 'non_existent_id';

        // Act
        $result = $this->repository->findById($nonExistentId);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Teste: Deve retornar todas as categorias salvas
     */
    public function test_should_return_all_saved_categories(): void
    {
        // Arrange
        $category1 = new Category('Categoria 1', 'Descrição 1');
        $category2 = new Category('Categoria 2', 'Descrição 2');
        
        $this->repository->save($category1);
        $this->repository->save($category2);

        // Act
        $allCategories = $this->repository->findAll();

        // Assert
        $this->assertIsArray($allCategories);
        $this->assertCount(2, $allCategories);
        $this->assertContainsOnlyInstancesOf(Category::class, $allCategories);
    }

    /**
     * Teste: Deve deletar uma categoria pelo ID
     */
    public function test_should_delete_category_by_id(): void
    {
        // Arrange
        $category = new Category('Categoria para deletar', 'Descrição');
        $savedCategory = $this->repository->save($category);

        // Act
        $result = $this->repository->delete($savedCategory->getId());

        // Assert
        $this->assertTrue($result);
        $this->assertNull($this->repository->findById($savedCategory->getId()));
    }

    /**
     * Teste: Deve retornar false ao tentar deletar categoria inexistente
     */
    public function test_should_return_false_when_deleting_non_existent_category(): void
    {
        // Arrange
        $nonExistentId = 'non_existent_id';

        // Act
        $result = $this->repository->delete($nonExistentId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve encontrar categorias ativas
     */
    public function test_should_find_active_categories(): void
    {
        // Arrange
        $activeCategory1 = new Category('Categoria Ativa 1', 'Descrição 1');
        $activeCategory2 = new Category('Categoria Ativa 2', 'Descrição 2');
        $inactiveCategory = new Category('Categoria Inativa', 'Descrição 3');
        
        $this->repository->save($activeCategory1);
        $this->repository->save($activeCategory2);
        $savedInactive = $this->repository->save($inactiveCategory);
        $savedInactive->deactivate();

        // Act
        $activeCategories = $this->repository->findActive();

        // Assert
        $this->assertCount(2, $activeCategories);
        foreach ($activeCategories as $category) {
            $this->assertTrue($category->isActive());
        }
    }

    /**
     * Teste: Deve encontrar categoria pelo nome
     */
    public function test_should_find_category_by_name(): void
    {
        // Arrange
        $category = new Category('Eletrônicos', 'Descrição');
        $this->repository->save($category);

        // Act
        $foundCategory = $this->repository->findByName('Eletrônicos');

        // Assert
        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals('Eletrônicos', $foundCategory->getName());
    }

    /**
     * Teste: Deve retornar null quando categoria não for encontrada pelo nome
     */
    public function test_should_return_null_when_category_not_found_by_name(): void
    {
        // Arrange
        $nonExistentName = 'Categoria Inexistente';

        // Act
        $result = $this->repository->findByName($nonExistentName);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Teste: Deve atualizar uma categoria existente quando já tem ID
     */
    public function test_should_update_existing_category_when_it_has_id(): void
    {
        // Arrange
        $originalCategory = new Category('Categoria Original', 'Descrição original');
        $savedCategory = $this->repository->save($originalCategory);
        
        // Modifica a categoria salva
        $savedCategory->updateName('Categoria Atualizada');
        $savedCategory->updateDescription('Nova descrição');

        // Act
        $updatedCategory = $this->repository->save($savedCategory);

        // Assert
        $this->assertSame($savedCategory, $updatedCategory);
        $this->assertEquals('Categoria Atualizada', $updatedCategory->getName());
        $this->assertEquals('Nova descrição', $updatedCategory->getDescription());
        
        // Verifica se foi realmente atualizada no repositório
        $foundCategory = $this->repository->findById($savedCategory->getId());
        $this->assertEquals('Categoria Atualizada', $foundCategory->getName());
        $this->assertEquals('Nova descrição', $foundCategory->getDescription());
    }

    /**
     * Teste: Deve manter o mesmo ID ao atualizar uma categoria existente
     */
    public function test_should_maintain_same_id_when_updating_existing_category(): void
    {
        // Arrange
        $originalCategory = new Category('Categoria Original', 'Descrição original');
        $savedCategory = $this->repository->save($originalCategory);
        $originalId = $savedCategory->getId();
        
        // Modifica a categoria
        $savedCategory->updateName('Categoria Modificada');

        // Act
        $updatedCategory = $this->repository->save($savedCategory);

        // Assert
        $this->assertEquals($originalId, $updatedCategory->getId());
        $this->assertCount(1, $this->repository->findAll());
    }

    /**
     * Teste: Deve criar categoria com ID específico quando fornecido
     */
    public function test_should_create_category_with_specific_id_when_provided(): void
    {
        // Arrange
        $specificId = 'custom_category_123';
        $category = new Category('Categoria Custom', 'Descrição custom', $specificId);

        // Act
        $savedCategory = $this->repository->save($category);

        // Assert
        $this->assertEquals($specificId, $savedCategory->getId());
        $this->assertSame($category, $savedCategory);
        
        // Verifica se foi salva corretamente
        $foundCategory = $this->repository->findById($specificId);
        $this->assertSame($category, $foundCategory);
    }
}
