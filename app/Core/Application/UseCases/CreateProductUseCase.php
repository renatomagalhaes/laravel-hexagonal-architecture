<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\DTOs\CreateProductDTO;
use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Use Case CreateProduct - Orquestra a criação de produtos
 * 
 * Este Use Case orquestra o fluxo de criação de produtos,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * Use Cases são responsáveis por:
 * - Orquestrar o fluxo de dados entre camadas
 * - Aplicar regras de negócio específicas do caso de uso
 * - Coordenar chamadas para repositórios e serviços
 * 
 * @package App\Core\Application\UseCases
 */
class CreateProductUseCase
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * Executa o caso de uso de criação de produto
     * 
     * @param CreateProductDTO $dto Dados para criação do produto
     * @return Product Produto criado
     * @throws \InvalidArgumentException Se os dados forem inválidos
     */
    public function execute(CreateProductDTO $dto): Product
    {
        // Cria a entidade Product (que já faz as validações através dos Value Objects)
        $product = new Product(
            $dto->name,
            $dto->price,
            $dto->categoryId,
            $dto->description
        );

        // Salva o produto através do repositório
        return $this->productRepository->save($product);
    }
}
