<?php

namespace App\Core\Domain\ValueObjects;

/**
 * Value Object Price
 * 
 * Representa o preço de um produto no domínio.
 * Value Objects são imutáveis e não possuem identidade própria.
 * 
 * @package App\Core\Domain\ValueObjects
 */
class Price
{
    private float $value;

    /**
     * Construtor do Price
     * 
     * @param float $value
     * @throws \InvalidArgumentException
     */
    public function __construct(float $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Valida o preço
     * 
     * @param float $value
     * @throws \InvalidArgumentException
     */
    private function validate(float $value): void
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
    }

    /**
     * Retorna o valor do preço
     * 
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Converte para float
     * 
     * @return float
     */
    public function __toFloat(): float
    {
        return $this->value;
    }

    /**
     * Verifica se é igual a outro Price
     * 
     * @param Price $other
     * @return bool
     */
    public function equals(Price $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Formata o preço para exibição
     * 
     * @return string
     */
    public function format(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }
}
