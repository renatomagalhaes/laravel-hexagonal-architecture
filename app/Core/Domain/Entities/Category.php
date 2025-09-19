<?php

namespace App\Core\Domain\Entities;

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
    private string $name;
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
        // Validações de regras de negócio
        $this->validateName($name);

        // Atribuição dos valores
        $this->id = $id ?? $this->generateId();
        $this->name = trim($name);
        $this->description = $description;
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Valida o nome da categoria
     * 
     * @param string $name
     * @throws \InvalidArgumentException
     */
    private function validateName(string $name): void
    {
        $trimmedName = trim($name);
        
        if (empty($trimmedName)) {
            throw new \InvalidArgumentException('Category name cannot be empty');
        }
        
        if (strlen($trimmedName) > 255) {
            throw new \InvalidArgumentException('Category name cannot exceed 255 characters');
        }
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
        $this->validateName($name);
        $this->name = trim($name);
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
        return $this->name;
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
