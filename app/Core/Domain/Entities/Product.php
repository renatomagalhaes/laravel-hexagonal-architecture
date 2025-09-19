<?php

namespace App\Core\Domain\Entities;

/**
 * Entidade Product - Representa um produto no domínio da aplicação
 * 
 * Esta entidade encapsula as regras de negócio relacionadas a produtos,
 * seguindo os princípios da arquitetura hexagonal onde o domínio
 * é independente de detalhes técnicos.
 * 
 * @package App\Core\Domain\Entities
 */
class Product
{
    private string $id;
    private string $name;
    private float $price;
    private int $categoryId;
    private string $description;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    /**
     * Construtor da entidade Product
     * 
     * @param string $name Nome do produto
     * @param float $price Preço do produto
     * @param int $categoryId ID da categoria
     * @param string $description Descrição do produto
     * @param string|null $id ID único (gerado automaticamente se não fornecido)
     */
    public function __construct(
        string $name,
        float $price,
        int $categoryId,
        string $description,
        ?string $id = null
    ) {
        // Validações de regras de negócio
        $this->validateName($name);
        $this->validatePrice($price);

        // Atribuição dos valores
        $this->id = $id ?? $this->generateId();
        $this->name = $name;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->description = $description;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Valida o nome do produto
     * 
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function validateName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
    }

    /**
     * Valida o preço do produto
     * 
     * @param float $price
     * @throws \InvalidArgumentException
     */
    private function validatePrice(float $price): void
    {
        if ($price < 0) {
            throw new \InvalidArgumentException('Product price cannot be negative');
        }
    }

    /**
     * Gera um ID único para o produto
     * 
     * @return string
     */
    private function generateId(): string
    {
        return uniqid('product_', true);
    }

    /**
     * Atualiza o nome do produto
     * 
     * @param string $name
     * @throws \InvalidArgumentException
     */
    public function updateName(string $name): void
    {
        $this->validateName($name);
        $this->name = $name;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Atualiza o preço do produto
     * 
     * @param float $price
     * @throws \InvalidArgumentException
     */
    public function updatePrice(float $price): void
    {
        $this->validatePrice($price);
        $this->price = $price;
        $this->updatedAt = new \DateTime();
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
