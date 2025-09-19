<?php

namespace Tests\Unit\Core\Domain\ValueObjects;

use App\Core\Domain\ValueObjects\Price;
use PHPUnit\Framework\TestCase;

/**
 * Teste do Value Object Price seguindo TDD
 * 
 * @package Tests\Unit\Core\Domain\ValueObjects
 */
class PriceTest extends TestCase
{
    /**
     * Teste: Deve criar um Price válido
     */
    public function test_should_create_valid_price(): void
    {
        // Arrange
        $priceValue = 1299.99;

        // Act
        $price = new Price($priceValue);

        // Assert
        $this->assertEquals($priceValue, $price->getValue());
        $this->assertEquals($priceValue, $price->getValue());
    }

    /**
     * Teste: Deve lançar exceção quando preço for negativo
     */
    public function test_should_throw_exception_when_price_is_negative(): void
    {
        // Arrange
        $negativePrice = -100.00;

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative');

        new Price($negativePrice);
    }

    /**
     * Teste: Deve permitir preço zero
     */
    public function test_should_allow_zero_price(): void
    {
        // Arrange
        $zeroPrice = 0.00;

        // Act
        $price = new Price($zeroPrice);

        // Assert
        $this->assertEquals($zeroPrice, $price->getValue());
    }

    /**
     * Teste: Deve ser igual a outro Price com mesmo valor
     */
    public function test_should_be_equal_to_another_price_with_same_value(): void
    {
        // Arrange
        $priceValue = 100.50;
        $price1 = new Price($priceValue);
        $price2 = new Price($priceValue);

        // Act & Assert
        $this->assertTrue($price1->equals($price2));
    }

    /**
     * Teste: Deve ser diferente de outro Price com valor diferente
     */
    public function test_should_not_be_equal_to_another_price_with_different_value(): void
    {
        // Arrange
        $price1 = new Price(100.00);
        $price2 = new Price(200.00);

        // Act & Assert
        $this->assertFalse($price1->equals($price2));
    }

    /**
     * Teste: Deve formatar o preço corretamente
     */
    public function test_should_format_price_correctly(): void
    {
        // Arrange
        $price = new Price(1299.99);

        // Act
        $formatted = $price->format();

        // Assert
        $this->assertEquals('R$ 1.299,99', $formatted);
    }

    /**
     * Teste: Deve converter para string corretamente
     */
    public function test_should_convert_to_string_correctly(): void
    {
        // Arrange
        $priceValue = 1299.99;
        $price = new Price($priceValue);

        // Act
        $stringValue = (string) $price;

        // Assert
        $this->assertEquals((string) $priceValue, $stringValue);
    }

    /**
     * Teste: Deve converter para float corretamente
     */
    public function test_should_convert_to_float_correctly(): void
    {
        // Arrange
        $priceValue = 1299.99;
        $price = new Price($priceValue);

        // Act
        $floatValue = $price->__toFloat();

        // Assert
        $this->assertEquals($priceValue, $floatValue);
        $this->assertIsFloat($floatValue);
    }
}
