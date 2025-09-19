<?php

namespace App\Http\Controllers;

use App\Core\Application\DTOs\CreateCategoryDTO;
use App\Core\Application\UseCases\CreateCategoryUseCase;
use App\Http\Requests\CreateCategoryRequest;
use Illuminate\Http\JsonResponse;

/**
 * Controller para gerenciar categorias
 * 
 * Responsável por receber requisições HTTP relacionadas a categorias,
 * validar dados de entrada e orquestrar os Use Cases correspondentes.
 * 
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    public function __construct(
        private CreateCategoryUseCase $createCategoryUseCase
    ) {
    }

    /**
     * Cria uma nova categoria
     * 
     * @param CreateCategoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        try {
            $dto = new CreateCategoryDTO(
                $request->input('name'),
                $request->input('description')
            );

            $category = $this->createCategoryUseCase->execute($dto);

            return new JsonResponse([
                'status' => 'success',
                'data' => $this->formatCategoryResponse($category)
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formata a resposta da categoria para JSON
     * 
     * @param \App\Core\Domain\Entities\Category $category
     * @return array
     */
    private function formatCategoryResponse($category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'is_active' => $category->isActive(),
            'created_at' => $category->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $category->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
