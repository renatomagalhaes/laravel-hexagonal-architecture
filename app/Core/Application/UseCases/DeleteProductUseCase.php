<?php

namespace App\Core\Application\UseCases;

use App\Core\Ports\Repositories\ProductRepository;

/**
 * Use Case para exclusão de produto
 * 
 * Orquestra a lógica de negócio para deletar um produto existente,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\UseCases
 */
class DeleteProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Executa a exclusão do produto
     * 
     * @param string $productId ID do produto a ser deletado
     * @return bool True se deletado com sucesso, false caso contrário
     * @throws \InvalidArgumentException
     */
    public function execute(string $productId): bool
    {
        // Verifica se o produto existe
        $existingProduct = $this->productRepository->findById($productId);
        
        if (!$existingProduct) {
            throw new \InvalidArgumentException('Product not found');
        }

        // Deleta o produto através do repositório
        return $this->productRepository->delete($productId);
    }
}
