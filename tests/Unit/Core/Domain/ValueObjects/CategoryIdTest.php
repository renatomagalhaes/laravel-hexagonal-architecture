<?php

namespace Tests\Unit\Core\Domain\ValueObjects;

use App\Core\Domain\ValueObjects\CategoryId;
use PHPUnit\Framework\TestCase;

/**
 * Teste do Value Object CategoryId seguindo TDD
 * 
 * Value Objects são objetos imutáveis que representam conceitos
 * do domínio sem identidade própria.
 * 
 * @package Tests\Unit\Core\Domain\ValueObjects
 */
class CategoryIdTest extends TestCase
{
    /**
     * Teste: Deve criar um CategoryId válido
     */
    public function test_should_create_valid_category_id(): void
    {
        // Arrange
        $idValue = 'category_123';

        // Act
        $categoryId = new CategoryId($idValue);

        // Assert
        $this->assertEquals($idValue, $categoryId->getValue());
        $this->assertEquals($idValue, (string) $categoryId);
    }

    /**
     * Teste: Deve lançar exceção quando ID for vazio
     */
    public function test_should_throw_exception_when_id_is_empty(): void
    {
        // Arrange
        $emptyId = '';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category ID cannot be empty');

        new CategoryId($emptyId);
    }

    /**
     * Teste: Deve lançar exceção quando ID for apenas espaços
     */
    public function test_should_throw_exception_when_id_is_only_spaces(): void
    {
        // Arrange
        $spacesOnly = '   ';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category ID cannot be empty');

        new CategoryId($spacesOnly);
    }

    /**
     * Teste: Deve ser igual a outro CategoryId com mesmo valor
     */
    public function test_should_be_equal_to_another_category_id_with_same_value(): void
    {
        // Arrange
        $idValue = 'category_123';
        $categoryId1 = new CategoryId($idValue);
        $categoryId2 = new CategoryId($idValue);

        // Act & Assert
        $this->assertTrue($categoryId1->equals($categoryId2));
    }

    /**
     * Teste: Deve ser diferente de outro CategoryId com valor diferente
     */
    public function test_should_not_be_equal_to_another_category_id_with_different_value(): void
    {
        // Arrange
        $categoryId1 = new CategoryId('category_123');
        $categoryId2 = new CategoryId('category_456');

        // Act & Assert
        $this->assertFalse($categoryId1->equals($categoryId2));
    }

    /**
     * Teste: Deve normalizar o ID (trim)
     */
    public function test_should_normalize_id_with_trim(): void
    {
        // Arrange
        $idWithSpaces = '  category_123  ';
        $expectedId = 'category_123';

        // Act
        $categoryId = new CategoryId($idWithSpaces);

        // Assert
        $this->assertEquals($expectedId, $categoryId->getValue());
    }

    /**
     * Teste: Deve aceitar IDs com diferentes formatos válidos
     */
    public function test_should_accept_different_valid_id_formats(): void
    {
        // Arrange & Act & Assert
        $this->assertInstanceOf(CategoryId::class, new CategoryId('category_123'));
        $this->assertInstanceOf(CategoryId::class, new CategoryId('cat_456'));
        $this->assertInstanceOf(CategoryId::class, new CategoryId('123'));
        $this->assertInstanceOf(CategoryId::class, new CategoryId('uuid-format-id'));
    }
}
