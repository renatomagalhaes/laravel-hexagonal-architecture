<?php

namespace Tests\Unit\Core\Domain\Entities;

use App\Core\Domain\Entities\Category;
use PHPUnit\Framework\TestCase;

/**
 * Teste da entidade Category seguindo TDD
 * 
 * Este teste define o comportamento esperado da entidade Category
 * antes de implementá-la, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Domain\Entities
 */
class CategoryTest extends TestCase
{
    /**
     * Teste: Deve criar uma categoria com dados válidos
     * 
     * Este é o primeiro teste que define o comportamento básico
     * esperado da entidade Category.
     */
    public function test_should_create_category_with_valid_data(): void
    {
        // Arrange - Preparar os dados de teste
        $name = 'Eletrônicos';
        $description = 'Produtos eletrônicos e tecnológicos';

        // Act - Executar a ação (criar a categoria)
        $category = new Category($name, $description);

        // Assert - Verificar se o comportamento está correto
        $this->assertEquals($name, $category->getName());
        $this->assertEquals($description, $category->getDescription());
        $this->assertNotNull($category->getId());
        $this->assertInstanceOf(\DateTime::class, $category->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $category->getUpdatedAt());
        $this->assertTrue($category->isActive());
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     * 
     * Testa as regras de negócio da entidade Category.
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $emptyName = '';
        $description = 'Descrição válida';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        new Category($emptyName, $description);
    }

    /**
     * Teste: Deve lançar exceção quando nome for apenas espaços
     */
    public function test_should_throw_exception_when_name_is_only_spaces(): void
    {
        // Arrange
        $spacesOnly = '   ';
        $description = 'Descrição válida';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        new Category($spacesOnly, $description);
    }

    /**
     * Teste: Deve lançar exceção quando nome for muito longo
     */
    public function test_should_throw_exception_when_name_is_too_long(): void
    {
        // Arrange
        $longName = str_repeat('a', 256); // Nome com mais de 255 caracteres
        $description = 'Descrição válida';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot exceed 255 characters');

        new Category($longName, $description);
    }

    /**
     * Teste: Deve permitir atualizar o nome da categoria
     * 
     * Testa o comportamento de atualização da entidade.
     */
    public function test_should_allow_updating_category_name(): void
    {
        // Arrange
        $category = new Category('Nome Original', 'Descrição original');
        $newName = 'Nome Atualizado';

        // Act
        $category->updateName($newName);

        // Assert
        $this->assertEquals($newName, $category->getName());
        $this->assertGreaterThan($category->getCreatedAt(), $category->getUpdatedAt());
    }

    /**
     * Teste: Deve permitir atualizar a descrição da categoria
     * 
     * Testa o comportamento de atualização da descrição.
     */
    public function test_should_allow_updating_category_description(): void
    {
        // Arrange
        $category = new Category('Categoria', 'Descrição original');
        $newDescription = 'Nova descrição';

        // Act
        $category->updateDescription($newDescription);

        // Assert
        $this->assertEquals($newDescription, $category->getDescription());
        $this->assertGreaterThan($category->getCreatedAt(), $category->getUpdatedAt());
    }

    /**
     * Teste: Deve lançar exceção ao tentar atualizar nome para vazio
     * 
     * Testa as validações nas operações de atualização.
     */
    public function test_should_throw_exception_when_updating_name_to_empty(): void
    {
        // Arrange
        $category = new Category('Nome Válido', 'Descrição válida');

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        $category->updateName('');
    }

    /**
     * Teste: Deve permitir ativar e desativar categoria
     * 
     * Testa o comportamento de ativação/desativação.
     */
    public function test_should_allow_activating_and_deactivating_category(): void
    {
        // Arrange
        $category = new Category('Categoria', 'Descrição');

        // Act & Assert - Deve começar ativa
        $this->assertTrue($category->isActive());

        // Act - Desativar
        $category->deactivate();

        // Assert
        $this->assertFalse($category->isActive());

        // Act - Reativar
        $category->activate();

        // Assert
        $this->assertTrue($category->isActive());
    }

    /**
     * Teste: Deve permitir criar categoria com ID específico
     * 
     * Testa a criação com ID customizado.
     */
    public function test_should_allow_creating_category_with_specific_id(): void
    {
        // Arrange
        $customId = 'custom_category_123';
        $name = 'Categoria Custom';
        $description = 'Descrição custom';

        // Act
        $category = new Category($name, $description, $customId);

        // Assert
        $this->assertEquals($customId, $category->getId());
        $this->assertEquals($name, $category->getName());
        $this->assertEquals($description, $category->getDescription());
    }
}
