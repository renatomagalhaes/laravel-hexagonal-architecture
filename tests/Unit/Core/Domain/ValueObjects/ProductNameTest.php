<?php

namespace Tests\Unit\Core\Domain\ValueObjects;

use App\Core\Domain\ValueObjects\ProductName;
use PHPUnit\Framework\TestCase;

/**
 * Teste do Value Object ProductName seguindo TDD
 * 
 * Value Objects são objetos imutáveis que representam conceitos
 * do domínio sem identidade própria.
 * 
 * @package Tests\Unit\Core\Domain\ValueObjects
 */
class ProductNameTest extends TestCase
{
    /**
     * Teste: Deve criar um ProductName válido
     */
    public function test_should_create_valid_product_name(): void
    {
        // Arrange
        $nameValue = 'Smartphone Samsung Galaxy';

        // Act
        $productName = new ProductName($nameValue);

        // Assert
        $this->assertEquals($nameValue, $productName->getValue());
        $this->assertEquals($nameValue, (string) $productName);
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
        $this->expectExceptionMessage('Product name cannot be empty');

        new ProductName($emptyName);
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
        $this->expectExceptionMessage('Product name cannot be empty');

        new ProductName($spacesOnly);
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
        $this->expectExceptionMessage('Product name cannot exceed 255 characters');

        new ProductName($longName);
    }

    /**
     * Teste: Deve ser igual a outro ProductName com mesmo valor
     */
    public function test_should_be_equal_to_another_product_name_with_same_value(): void
    {
        // Arrange
        $nameValue = 'Smartphone';
        $productName1 = new ProductName($nameValue);
        $productName2 = new ProductName($nameValue);

        // Act & Assert
        $this->assertTrue($productName1->equals($productName2));
    }

    /**
     * Teste: Deve ser diferente de outro ProductName com valor diferente
     */
    public function test_should_not_be_equal_to_another_product_name_with_different_value(): void
    {
        // Arrange
        $productName1 = new ProductName('Smartphone');
        $productName2 = new ProductName('Tablet');

        // Act & Assert
        $this->assertFalse($productName1->equals($productName2));
    }
}
