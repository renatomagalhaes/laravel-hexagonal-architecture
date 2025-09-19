<?php

namespace App\Core\Ports\Repositories;

use App\Core\Domain\Entities\Product;

/**
 * Interface ProductRepository - Port para operações de persistência de produtos
 * 
 * Esta interface define o contrato para operações de persistência
 * de produtos, seguindo os princípios da arquitetura hexagonal.
 * 
 * O Port (interface) é independente de detalhes de implementação,
 * permitindo que diferentes adapters (implementações) sejam usados
 * sem afetar o domínio.
 * 
 * @package App\Core\Ports\Repositories
 */
interface ProductRepository
{
    /**
     * Salva um produto no repositório
     * 
     * @param Product $product Produto a ser salvo
     * @return Product Produto salvo com ID gerado
     */
    public function save(Product $product): Product;

    /**
     * Busca um produto pelo ID
     * 
     * @param string $id ID do produto
     * @return Product|null Produto encontrado ou null se não existir
     */
    public function findById(string $id): ?Product;

    /**
     * Busca todos os produtos
     * 
     * @return array Lista de produtos
     */
    public function findAll(): array;

    /**
     * Remove um produto do repositório
     * 
     * @param string $id ID do produto a ser removido
     * @return bool True se removido com sucesso, false caso contrário
     */
    public function delete(string $id): bool;

    /**
     * Busca produtos por categoria
     * 
     * @param int $categoryId ID da categoria
     * @return array Lista de produtos da categoria
     */
    public function findByCategoryId(int $categoryId): array;
}
