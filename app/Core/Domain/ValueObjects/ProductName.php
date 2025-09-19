<?php

namespace App\Core\Domain\ValueObjects;

/**
 * Value Object ProductName
 * 
 * Representa o nome de um produto no domínio.
 * Value Objects são imutáveis e não possuem identidade própria.
 * 
 * @package App\Core\Domain\ValueObjects
 */
class ProductName
{
    private const MAX_LENGTH = 255;
    
    private string $value;

    /**
     * Construtor do ProductName
     * 
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = trim($value);
    }

    /**
     * Valida o nome do produto
     * 
     * @param string $value
     * @throws \InvalidArgumentException
     */
    private function validate(string $value): void
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
        
        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Product name cannot exceed 255 characters');
        }
    }

    /**
     * Retorna o valor do nome
     * 
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Converte para string
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Verifica se é igual a outro ProductName
     * 
     * @param ProductName $other
     * @return bool
     */
    public function equals(ProductName $other): bool
    {
        return $this->value === $other->value;
    }
}
