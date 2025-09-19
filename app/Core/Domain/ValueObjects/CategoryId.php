<?php

namespace App\Core\Domain\ValueObjects;

/**
 * Value Object CategoryId
 * 
 * Representa o ID de uma categoria no domínio.
 * Value Objects são imutáveis e não possuem identidade própria.
 * 
 * @package App\Core\Domain\ValueObjects
 */
class CategoryId
{
    private string $value;

    /**
     * Construtor do CategoryId
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
     * Valida o ID da categoria
     * 
     * @param string $value
     * @throws \InvalidArgumentException
     */
    private function validate(string $value): void
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            throw new \InvalidArgumentException('Category ID cannot be empty');
        }
    }

    /**
     * Retorna o valor do ID
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
     * Verifica se é igual a outro CategoryId
     * 
     * @param CategoryId $other
     * @return bool
     */
    public function equals(CategoryId $other): bool
    {
        return $this->value === $other->value;
    }
}
