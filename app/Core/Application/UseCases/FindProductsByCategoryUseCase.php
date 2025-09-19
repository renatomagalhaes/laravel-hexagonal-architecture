<?php

namespace App\Core\Application\UseCases;

use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Use Case para busca de produtos por categoria
 * 
 * Orquestra a lógica de negócio para buscar produtos de uma categoria específica,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\UseCases
 */
class FindProductsByCategoryUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Executa a busca de produtos por categoria
     * 
     * @param string $categoryId ID da categoria
     * @return array Lista de produtos da categoria
     */
    public function execute(string $categoryId): array
    {
        return $this->productRepository->findByCategoryId($categoryId);
    }
}
