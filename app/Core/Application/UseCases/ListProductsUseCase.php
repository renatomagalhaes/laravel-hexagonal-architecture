<?php

namespace App\Core\Application\UseCases;

use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Use Case para listagem de produtos
 * 
 * Orquestra a lógica de negócio para listar todos os produtos,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\UseCases
 */
class ListProductsUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Executa a listagem de produtos
     * 
     * @return array Lista de produtos
     */
    public function execute(): array
    {
        return $this->productRepository->findAll();
    }
}
