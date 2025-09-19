<?php

namespace App\Core\Domain\Services;

use App\Core\Ports\Repositories\CategoryRepository;

/**
 * Domain Service para regras de negócio complexas relacionadas a categorias
 * 
 * Domain Services encapsulam regras de negócio que não pertencem
 * naturalmente a uma única entidade, mas envolvem múltiplas entidades
 * ou regras complexas do domínio.
 * 
 * @package App\Core\Domain\Services
 */
class CategoryDomainService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Verifica se o nome da categoria é único
     * 
     * @param string $categoryName Nome da categoria
     * @param string|null $excludeCategoryId ID da categoria a ser excluída da verificação
     * @return bool True se o nome for único, false caso contrário
     */
    public function isCategoryNameUnique(string $categoryName, ?string $excludeCategoryId = null): bool
    {
        $existingCategory = $this->categoryRepository->findByName($categoryName);
        
        if ($existingCategory === null) {
            return true;
        }

        // Se foi especificado um ID para excluir, verifica se é o mesmo
        if ($excludeCategoryId !== null && $existingCategory->getId() === $excludeCategoryId) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se uma categoria pode ser criada
     * 
     * @param string $categoryName Nome da categoria
     * @param string|null $excludeCategoryId ID da categoria a ser excluída da verificação
     * @return bool True se a categoria pode ser criada, false caso contrário
     */
    public function canCreateCategory(string $categoryName, ?string $excludeCategoryId = null): bool
    {
        return $this->isCategoryNameUnique($categoryName, $excludeCategoryId);
    }

    /**
     * Verifica se uma categoria pode ser ativada
     * 
     * @param string $categoryId ID da categoria
     * @return bool True se a categoria pode ser ativada, false caso contrário
     */
    public function canActivateCategory(string $categoryId): bool
    {
        $category = $this->categoryRepository->findById($categoryId);
        
        if (!$category) {
            return false;
        }

        // Só pode ativar se estiver inativa
        return !$category->isActive();
    }

    /**
     * Verifica se uma categoria pode ser desativada
     * 
     * @param string $categoryId ID da categoria
     * @return bool True se a categoria pode ser desativada, false caso contrário
     */
    public function canDeactivateCategory(string $categoryId): bool
    {
        $category = $this->categoryRepository->findById($categoryId);
        
        if (!$category) {
            return false;
        }

        // Só pode desativar se estiver ativa
        return $category->isActive();
    }

    /**
     * Obtém estatísticas de categorias
     * 
     * @return array Estatísticas das categorias
     */
    public function getCategoryStatistics(): array
    {
        $allCategories = $this->categoryRepository->findAll();
        $activeCategories = $this->categoryRepository->findActive();
        
        return [
            'total' => count($allCategories),
            'active' => count($activeCategories),
            'inactive' => count($allCategories) - count($activeCategories),
        ];
    }

    /**
     * Verifica se uma categoria tem produtos associados
     * 
     * @param string $categoryId ID da categoria
     * @return bool True se a categoria tem produtos, false caso contrário
     */
    public function hasProducts(string $categoryId): bool
    {
        // Esta implementação seria mais complexa em um cenário real
        // onde precisaríamos acessar o ProductRepository
        // Por simplicidade, retornamos false
        return false;
    }

    /**
     * Verifica se uma categoria pode ser deletada
     * 
     * @param string $categoryId ID da categoria
     * @return bool True se a categoria pode ser deletada, false caso contrário
     */
    public function canDeleteCategory(string $categoryId): bool
    {
        $category = $this->categoryRepository->findById($categoryId);
        
        if (!$category) {
            return false;
        }

        // Não pode deletar se tiver produtos associados
        if ($this->hasProducts($categoryId)) {
            return false;
        }

        return true;
    }
}
