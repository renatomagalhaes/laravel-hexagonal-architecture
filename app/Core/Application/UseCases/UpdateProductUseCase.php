<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\DTOs\UpdateProductDTO;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Use Case para atualização de produto
 * 
 * Orquestra a lógica de negócio para atualizar um produto existente,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\UseCases
 */
class UpdateProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Executa a atualização do produto
     * 
     * @param UpdateProductDTO $dto Dados para atualização
     * @return Product Produto atualizado
     * @throws \InvalidArgumentException
     */
    public function execute(UpdateProductDTO $dto): Product
    {
        // Busca o produto existente
        $existingProduct = $this->productRepository->findById($dto->id);
        
        if (!$existingProduct) {
            throw new \InvalidArgumentException('Product not found');
        }

        // Atualiza os dados do produto
        $existingProduct->updateName($dto->name);
        $existingProduct->updatePrice($dto->price);
        $existingProduct->updateCategory($dto->categoryId);
        $existingProduct->updateDescription($dto->description);

        // Salva o produto atualizado através do repositório
        return $this->productRepository->save($existingProduct);
    }
}
