<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\ValueObjects\ProductName;
use App\Core\Domain\ValueObjects\Price;
use App\Core\Domain\ValueObjects\CategoryId;

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
    private ProductName $name;
    private Price $price;
    private CategoryId $categoryId;
    private string $description;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    /**
     * Construtor da entidade Product
     * 
     * @param string $name Nome do produto
     * @param float $price Preço do produto
     * @param string $categoryId ID da categoria
     * @param string $description Descrição do produto
     * @param string|null $id ID único (gerado automaticamente se não fornecido)
     */
    public function __construct(
        string $name,
        float $price,
        string $categoryId,
        string $description,
        ?string $id = null
    ) {
        // Criação dos Value Objects (que já fazem as validações)
        $this->name = new ProductName($name);
        $this->price = new Price($price);
        $this->categoryId = new CategoryId($categoryId);

        // Atribuição dos valores
        $this->id = $id ?? $this->generateId();
        $this->description = $description;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
        $this->name = new ProductName($name);
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
        $this->price = new Price($price);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Atualiza a categoria do produto
     * 
     * @param string $categoryId
     * @throws \InvalidArgumentException
     */
    public function updateCategory(string $categoryId): void
    {
        $this->categoryId = new CategoryId($categoryId);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Atualiza a descrição do produto
     * 
     * @param string $description
     */
    public function updateDescription(string $description): void
    {
        $this->description = $description;
        $this->updatedAt = new \DateTime();
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }

    public function getPrice(): float
    {
        return $this->price->getValue();
    }

    public function getCategoryId(): string
    {
        return $this->categoryId->getValue();
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
