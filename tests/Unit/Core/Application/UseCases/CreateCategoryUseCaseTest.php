<?php

namespace Tests\Unit\Core\Application\UseCases;

use App\Core\Application\DTOs\CreateCategoryDTO;
use App\Core\Application\UseCases\CreateCategoryUseCase;
use App\Core\Domain\Entities\Category;
use App\Core\Ports\Repositories\CategoryRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Use Case CreateCategory seguindo TDD
 * 
 * Este teste define o comportamento esperado do Use Case CreateCategory
 * antes de implementá-lo, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Application\UseCases
 */
class CreateCategoryUseCaseTest extends TestCase
{
    private CreateCategoryUseCase $useCase;
    private CategoryRepository|MockObject $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock do repositório
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        
        // Instância do Use Case com dependência injetada
        $this->useCase = new CreateCategoryUseCase($this->categoryRepository);
    }

    /**
     * Teste: Deve criar uma categoria com dados válidos
     */
    public function test_should_create_category_with_valid_data(): void
    {
        // Arrange
        $dto = new CreateCategoryDTO(
            'Eletrônicos',
            'Produtos eletrônicos e tecnológicos'
        );

        $expectedCategory = new Category(
            $dto->name,
            $dto->description
        );

        // Mock do repositório
        $this->categoryRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Category::class))
            ->willReturn($expectedCategory);

        // Act
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($dto->name, $result->getName());
        $this->assertEquals($dto->description, $result->getDescription());
        $this->assertTrue($result->isActive());
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $dto = new CreateCategoryDTO(
            '', // Nome vazio
            'Descrição válida'
        );

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        $this->useCase->execute($dto);
    }

    /**
     * Teste: Deve lançar exceção quando nome for apenas espaços
     */
    public function test_should_throw_exception_when_name_is_only_spaces(): void
    {
        // Arrange
        $dto = new CreateCategoryDTO(
            '   ', // Apenas espaços
            'Descrição válida'
        );

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        $this->useCase->execute($dto);
    }

    /**
     * Teste: Deve lançar exceção quando nome for muito longo
     */
    public function test_should_throw_exception_when_name_is_too_long(): void
    {
        // Arrange
        $longName = str_repeat('a', 256); // Nome com mais de 255 caracteres
        $dto = new CreateCategoryDTO(
            $longName,
            'Descrição válida'
        );

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot exceed 255 characters');

        $this->useCase->execute($dto);
    }

    /**
     * Teste: Deve chamar o repositório para salvar a categoria
     */
    public function test_should_call_repository_to_save_category(): void
    {
        // Arrange
        $dto = new CreateCategoryDTO(
            'Categoria Teste',
            'Descrição teste'
        );

        // Mock do repositório
        $this->categoryRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Category $category) use ($dto) {
                return $category->getName() === $dto->name &&
                       $category->getDescription() === $dto->description &&
                       $category->isActive() === true;
            }))
            ->willReturn(new Category($dto->name, $dto->description));

        // Act
        $this->useCase->execute($dto);

        // Assert - O mock já verifica se o método foi chamado corretamente
        $this->assertTrue(true);
    }

    /**
     * Teste: Deve retornar a categoria salva pelo repositório
     */
    public function test_should_return_category_saved_by_repository(): void
    {
        // Arrange
        $dto = new CreateCategoryDTO(
            'Categoria Teste',
            'Descrição teste'
        );

        $savedCategory = new Category(
            $dto->name,
            $dto->description,
            'category_123' // ID gerado pelo repositório
        );

        // Mock do repositório
        $this->categoryRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($savedCategory);

        // Act
        $result = $this->useCase->execute($dto);

        // Assert
        $this->assertSame($savedCategory, $result);
        $this->assertEquals('category_123', $result->getId());
    }
}
