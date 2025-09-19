<?php

namespace App\Core\Domain\ValueObjects;

/**
 * Value Object CategoryName
 * 
 * Representa o nome de uma categoria no domínio.
 * Value Objects são imutáveis e não possuem identidade própria.
 * 
 * @package App\Core\Domain\ValueObjects
 */
class CategoryName
{
    private const MAX_LENGTH = 255;
    
    private string $value;

    /**
     * Construtor do CategoryName
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
     * Valida o nome da categoria
     * 
     * @param string $value
     * @throws \InvalidArgumentException
     */
    private function validate(string $value): void
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            throw new \InvalidArgumentException('Category name cannot be empty');
        }
        
        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Category name cannot exceed 255 characters');
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
     * Verifica se é igual a outro CategoryName
     * 
     * @param CategoryName $other
     * @return bool
     */
    public function equals(CategoryName $other): bool
    {
        return $this->value === $other->value;
    }
}
