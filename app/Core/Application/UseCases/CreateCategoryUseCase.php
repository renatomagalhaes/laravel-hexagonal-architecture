<?php

namespace App\Core\Application\UseCases;

use App\Core\Application\DTOs\CreateCategoryDTO;
use App\Core\Domain\Entities\Category;
use App\Core\Ports\Repositories\CategoryRepository;

/**
 * Use Case CreateCategory - Orquestra a criação de categorias
 * 
 * Este Use Case orquestra o fluxo de criação de categorias,
 * seguindo os princípios da arquitetura hexagonal.
 * 
 * Use Cases são responsáveis por:
 * - Orquestrar o fluxo de dados entre camadas
 * - Aplicar regras de negócio específicas do caso de uso
 * - Coordenar chamadas para repositórios e serviços
 * 
 * @package App\Core\Application\UseCases
 */
class CreateCategoryUseCase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Executa o caso de uso de criação de categoria
     * 
     * @param CreateCategoryDTO $dto Dados para criação da categoria
     * @return Category Categoria criada
     * @throws \InvalidArgumentException Se os dados forem inválidos
     */
    public function execute(CreateCategoryDTO $dto): Category
    {
        // Cria a entidade Category (que já faz as validações através dos Value Objects)
        $category = new Category(
            $dto->name,
            $dto->description
        );

        // Salva a categoria através do repositório
        return $this->categoryRepository->save($category);
    }
}
