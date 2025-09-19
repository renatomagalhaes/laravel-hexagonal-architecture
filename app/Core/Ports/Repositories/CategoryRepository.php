<?php

namespace App\Core\Ports\Repositories;

use App\Core\Domain\Entities\Category;

/**
 * Interface CategoryRepository - Port para operações de persistência de categorias
 * 
 * Esta interface define o contrato para operações de persistência
 * de categorias, seguindo os princípios da arquitetura hexagonal.
 * 
 * O Port (interface) é independente de detalhes de implementação,
 * permitindo que diferentes adapters (implementações) sejam usados
 * sem afetar o domínio.
 * 
 * @package App\Core\Ports\Repositories
 */
interface CategoryRepository
{
    /**
     * Salva uma categoria no repositório
     * 
     * @param Category $category Categoria a ser salva
     * @return Category Categoria salva com ID gerado
     */
    public function save(Category $category): Category;

    /**
     * Busca uma categoria pelo ID
     * 
     * @param string $id ID da categoria
     * @return Category|null Categoria encontrada ou null se não existir
     */
    public function findById(string $id): ?Category;

    /**
     * Busca todas as categorias
     * 
     * @return array Lista de categorias
     */
    public function findAll(): array;

    /**
     * Remove uma categoria do repositório
     * 
     * @param string $id ID da categoria a ser removida
     * @return bool True se removida com sucesso, false caso contrário
     */
    public function delete(string $id): bool;

    /**
     * Busca categorias ativas
     * 
     * @return array Lista de categorias ativas
     */
    public function findActive(): array;

    /**
     * Busca uma categoria pelo nome
     * 
     * @param string $name Nome da categoria
     * @return Category|null Categoria encontrada ou null se não existir
     */
    public function findByName(string $name): ?Category;
}
