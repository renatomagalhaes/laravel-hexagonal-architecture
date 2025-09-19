<?php

namespace App\Core\Infrastructure\Database;

use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Implementação InMemory do ProductRepository
 * 
 * Esta implementação armazena produtos em memória,
 * útil para testes e desenvolvimento.
 * 
 * @package App\Core\Infrastructure\Database
 */
class InMemoryProductRepository implements ProductRepository
{
    private array $products = [];
    private int $nextId = 1;

    /**
     * Salva um produto no repositório
     * 
     * @param Product $product Produto a ser salvo
     * @return Product Produto salvo com ID gerado
     */
    public function save(Product $product): Product
    {
        // Se o produto já tem ID, atualiza; senão, cria novo
        if ($product->getId()) {
            $this->products[$product->getId()] = $product;
            return $product;
        }

        // Gera novo ID e cria produto com ID
        $id = 'product_' . $this->nextId++;
        $productWithId = new Product(
            $product->getName(),
            $product->getPrice(),
            $product->getCategoryId(),
            $product->getDescription(),
            $id
        );

        $this->products[$id] = $productWithId;
        return $productWithId;
    }

    /**
     * Busca um produto pelo ID
     * 
     * @param string $id ID do produto
     * @return Product|null Produto encontrado ou null se não existir
     */
    public function findById(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    /**
     * Busca todos os produtos
     * 
     * @return array Lista de produtos
     */
    public function findAll(): array
    {
        return array_values($this->products);
    }

    /**
     * Remove um produto do repositório
     * 
     * @param string $id ID do produto a ser removido
     * @return bool True se removido com sucesso, false caso contrário
     */
    public function delete(string $id): bool
    {
        if (!isset($this->products[$id])) {
            return false;
        }

        unset($this->products[$id]);
        return true;
    }

    /**
     * Busca produtos por categoria
     * 
     * @param int $categoryId ID da categoria
     * @return array Lista de produtos da categoria
     */
    public function findByCategoryId(int $categoryId): array
    {
        return array_filter(
            $this->products,
            fn(Product $product) => $product->getCategoryId() === $categoryId
        );
    }
}
