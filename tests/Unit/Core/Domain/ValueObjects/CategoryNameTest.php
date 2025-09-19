<?php

namespace Tests\Unit\Core\Domain\ValueObjects;

use App\Core\Domain\ValueObjects\CategoryName;
use PHPUnit\Framework\TestCase;

/**
 * Teste do Value Object CategoryName seguindo TDD
 * 
 * Value Objects são objetos imutáveis que representam conceitos
 * do domínio sem identidade própria.
 * 
 * @package Tests\Unit\Core\Domain\ValueObjects
 */
class CategoryNameTest extends TestCase
{
    /**
     * Teste: Deve criar um CategoryName válido
     */
    public function test_should_create_valid_category_name(): void
    {
        // Arrange
        $nameValue = 'Eletrônicos';

        // Act
        $categoryName = new CategoryName($nameValue);

        // Assert
        $this->assertEquals($nameValue, $categoryName->getValue());
        $this->assertEquals($nameValue, (string) $categoryName);
    }

    /**
     * Teste: Deve lançar exceção quando nome for vazio
     */
    public function test_should_throw_exception_when_name_is_empty(): void
    {
        // Arrange
        $emptyName = '';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        new CategoryName($emptyName);
    }

    /**
     * Teste: Deve lançar exceção quando nome for apenas espaços
     */
    public function test_should_throw_exception_when_name_is_only_spaces(): void
    {
        // Arrange
        $spacesOnly = '   ';

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot be empty');

        new CategoryName($spacesOnly);
    }

    /**
     * Teste: Deve lançar exceção quando nome for muito longo
     */
    public function test_should_throw_exception_when_name_is_too_long(): void
    {
        // Arrange
        $longName = str_repeat('a', 256); // Nome com mais de 255 caracteres

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Category name cannot exceed 255 characters');

        new CategoryName($longName);
    }

    /**
     * Teste: Deve ser igual a outro CategoryName com mesmo valor
     */
    public function test_should_be_equal_to_another_category_name_with_same_value(): void
    {
        // Arrange
        $nameValue = 'Eletrônicos';
        $categoryName1 = new CategoryName($nameValue);
        $categoryName2 = new CategoryName($nameValue);

        // Act & Assert
        $this->assertTrue($categoryName1->equals($categoryName2));
    }

    /**
     * Teste: Deve ser diferente de outro CategoryName com valor diferente
     */
    public function test_should_not_be_equal_to_another_category_name_with_different_value(): void
    {
        // Arrange
        $categoryName1 = new CategoryName('Eletrônicos');
        $categoryName2 = new CategoryName('Roupas');

        // Act & Assert
        $this->assertFalse($categoryName1->equals($categoryName2));
    }

    /**
     * Teste: Deve normalizar o nome (trim)
     */
    public function test_should_normalize_name_with_trim(): void
    {
        // Arrange
        $nameWithSpaces = '  Eletrônicos  ';
        $expectedName = 'Eletrônicos';

        // Act
        $categoryName = new CategoryName($nameWithSpaces);

        // Assert
        $this->assertEquals($expectedName, $categoryName->getValue());
    }
}
