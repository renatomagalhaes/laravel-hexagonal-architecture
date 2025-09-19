<?php

namespace Tests\Unit\Core\Domain\Services;

use App\Core\Domain\Entities\Category;
use App\Core\Domain\Services\CategoryDomainService;
use App\Core\Ports\Repositories\CategoryRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Domain Service CategoryDomainService seguindo TDD
 * 
 * Domain Services encapsulam regras de negócio complexas que
 * não pertencem naturalmente a uma única entidade.
 * 
 * @package Tests\Unit\Core\Domain\Services
 */
class CategoryDomainServiceTest extends TestCase
{
    private CategoryRepository|MockObject $categoryRepository;
    private CategoryDomainService $categoryDomainService;

    protected function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->categoryDomainService = new CategoryDomainService($this->categoryRepository);
    }

    /**
     * Teste: Deve validar se nome da categoria é único
     */
    public function test_should_validate_category_name_is_unique(): void
    {
        // Arrange
        $categoryName = 'Categoria Única';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with($categoryName)
            ->willReturn(null);

        // Act
        $result = $this->categoryDomainService->isCategoryNameUnique($categoryName);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando nome da categoria já existe
     */
    public function test_should_return_false_when_category_name_already_exists(): void
    {
        // Arrange
        $categoryName = 'Categoria Existente';
        $existingCategory = new Category($categoryName, 'Descrição', 'category_1');

        $this->categoryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with($categoryName)
            ->willReturn($existingCategory);

        // Act
        $result = $this->categoryDomainService->isCategoryNameUnique($categoryName);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se categoria pode ser criada
     */
    public function test_should_validate_category_can_be_created(): void
    {
        // Arrange
        $categoryName = 'Categoria Válida';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with($categoryName)
            ->willReturn(null);

        // Act
        $result = $this->categoryDomainService->canCreateCategory($categoryName);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando nome da categoria já existe para criação
     */
    public function test_should_return_false_when_category_name_exists_for_creation(): void
    {
        // Arrange
        $categoryName = 'Categoria Existente';
        $existingCategory = new Category($categoryName, 'Descrição', 'category_1');

        $this->categoryRepository
            ->expects($this->once())
            ->method('findByName')
            ->with($categoryName)
            ->willReturn($existingCategory);

        // Act
        $result = $this->categoryDomainService->canCreateCategory($categoryName);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se categoria pode ser ativada
     */
    public function test_should_validate_category_can_be_activated(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $inactiveCategory = new Category('Categoria Inativa', 'Descrição', $categoryId);
        $inactiveCategory->deactivate();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($inactiveCategory);

        // Act
        $result = $this->categoryDomainService->canActivateCategory($categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando categoria já está ativa
     */
    public function test_should_return_false_when_category_is_already_active(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        // Act
        $result = $this->categoryDomainService->canActivateCategory($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não existe para ativação
     */
    public function test_should_return_false_when_category_does_not_exist_for_activation(): void
    {
        // Arrange
        $categoryId = 'category_inexistente';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn(null);

        // Act
        $result = $this->categoryDomainService->canActivateCategory($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se categoria pode ser desativada
     */
    public function test_should_validate_category_can_be_deactivated(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        // Act
        $result = $this->categoryDomainService->canDeactivateCategory($categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando categoria já está inativa
     */
    public function test_should_return_false_when_category_is_already_inactive(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $inactiveCategory = new Category('Categoria Inativa', 'Descrição', $categoryId);
        $inactiveCategory->deactivate();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($inactiveCategory);

        // Act
        $result = $this->categoryDomainService->canDeactivateCategory($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não existe para desativação
     */
    public function test_should_return_false_when_category_does_not_exist_for_deactivation(): void
    {
        // Arrange
        $categoryId = 'category_inexistente';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn(null);

        // Act
        $result = $this->categoryDomainService->canDeactivateCategory($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve obter estatísticas das categorias
     */
    public function test_should_get_category_statistics(): void
    {
        // Arrange
        $allCategories = [
            new Category('Categoria 1', 'Descrição 1', 'category_1'),
            new Category('Categoria 2', 'Descrição 2', 'category_2'),
        ];
        
        $activeCategories = [
            new Category('Categoria 1', 'Descrição 1', 'category_1'),
        ];

        $this->categoryRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($allCategories);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findActive')
            ->willReturn($activeCategories);

        // Act
        $result = $this->categoryDomainService->getCategoryStatistics();

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(2, $result['total']);
        $this->assertEquals(1, $result['active']);
        $this->assertEquals(1, $result['inactive']);
    }

    /**
     * Teste: Deve retornar false quando categoria tem produtos associados
     */
    public function test_should_return_false_when_category_has_products(): void
    {
        // Arrange
        $categoryId = 'category_1';

        // Act
        $result = $this->categoryDomainService->hasProducts($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se categoria pode ser deletada
     */
    public function test_should_validate_category_can_be_deleted(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $category = new Category('Categoria', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($category);

        // Act
        $result = $this->categoryDomainService->canDeleteCategory($categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não existe para exclusão
     */
    public function test_should_return_false_when_category_does_not_exist_for_deletion(): void
    {
        // Arrange
        $categoryId = 'category_inexistente';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn(null);

        // Act
        $result = $this->categoryDomainService->canDeleteCategory($categoryId);

        // Assert
        $this->assertFalse($result);
    }
}
