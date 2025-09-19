<?php

namespace App\Core\Infrastructure\Database;

use App\Core\Domain\Entities\Category;
use App\Core\Ports\Repositories\CategoryRepository;

/**
 * Implementação InMemory do CategoryRepository
 * 
 * Esta implementação armazena categorias em memória,
 * útil para testes e desenvolvimento.
 * 
 * @package App\Core\Infrastructure\Database
 */
class InMemoryCategoryRepository implements CategoryRepository
{
    private array $categories = [];

    /**
     * Salva uma categoria no repositório
     * 
     * @param Category $category Categoria a ser salva
     * @return Category Categoria salva com ID gerado
     */
    public function save(Category $category): Category
    {
        // Sempre salva a categoria com seu ID (a entidade Category sempre gera um ID)
        $this->categories[$category->getId()] = $category;
        return $category;
    }

    /**
     * Busca uma categoria pelo ID
     * 
     * @param string $id ID da categoria
     * @return Category|null Categoria encontrada ou null se não existir
     */
    public function findById(string $id): ?Category
    {
        return $this->categories[$id] ?? null;
    }

    /**
     * Busca todas as categorias
     * 
     * @return array Lista de categorias
     */
    public function findAll(): array
    {
        return array_values($this->categories);
    }

    /**
     * Remove uma categoria do repositório
     * 
     * @param string $id ID da categoria a ser removida
     * @return bool True se removida com sucesso, false caso contrário
     */
    public function delete(string $id): bool
    {
        if (!isset($this->categories[$id])) {
            return false;
        }

        unset($this->categories[$id]);
        return true;
    }

    /**
     * Busca categorias ativas
     * 
     * @return array Lista de categorias ativas
     */
    public function findActive(): array
    {
        return array_filter(
            $this->categories,
            fn(Category $category) => $category->isActive()
        );
    }

    /**
     * Busca uma categoria pelo nome
     * 
     * @param string $name Nome da categoria
     * @return Category|null Categoria encontrada ou null se não existir
     */
    public function findByName(string $name): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->getName() === $name) {
                return $category;
            }
        }

        return null;
    }
}
