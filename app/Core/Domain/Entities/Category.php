<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\ValueObjects\CategoryName;

/**
 * Entidade Category - Representa uma categoria no domínio da aplicação
 * 
 * Esta entidade encapsula as regras de negócio relacionadas a categorias,
 * seguindo os princípios da arquitetura hexagonal onde o domínio
 * é independente de detalhes técnicos.
 * 
 * @package App\Core\Domain\Entities
 */
class Category
{
    private string $id;
    private CategoryName $name;
    private string $description;
    private bool $isActive;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    /**
     * Construtor da entidade Category
     * 
     * @param string $name Nome da categoria
     * @param string $description Descrição da categoria
     * @param string|null $id ID único (gerado automaticamente se não fornecido)
     */
    public function __construct(
        string $name,
        string $description,
        ?string $id = null
    ) {
        // Criação do Value Object (que já faz as validações)
        $this->name = new CategoryName($name);

        // Atribuição dos valores
        $this->id = $id ?? $this->generateId();
        $this->description = $description;
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }


    /**
     * Gera um ID único para a categoria
     * 
     * @return string
     */
    private function generateId(): string
    {
        return uniqid('category_', true);
    }

    /**
     * Atualiza o nome da categoria
     * 
     * @param string $name
     * @throws \InvalidArgumentException
     */
    public function updateName(string $name): void
    {
        $this->name = new CategoryName($name);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Atualiza a descrição da categoria
     * 
     * @param string $description
     */
    public function updateDescription(string $description): void
    {
        $this->description = $description;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Ativa a categoria
     */
    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new \DateTime();
    }

    /**
     * Desativa a categoria
     */
    public function deactivate(): void
    {
        $this->isActive = false;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
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
