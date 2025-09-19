<?php

namespace App\Http\Controllers;

use App\Core\Application\DTOs\CreateProductDTO;
use App\Core\Application\DTOs\UpdateProductDTO;
use App\Core\Application\UseCases\CreateProductUseCase;
use App\Core\Application\UseCases\DeleteProductUseCase;
use App\Core\Application\UseCases\FindProductsByCategoryUseCase;
use App\Core\Application\UseCases\ListProductsUseCase;
use App\Core\Application\UseCases\UpdateProductUseCase;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller para gerenciar produtos
 * 
 * Responsável por receber requisições HTTP relacionadas a produtos,
 * validar dados de entrada e orquestrar os Use Cases correspondentes.
 * 
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    public function __construct(
        private CreateProductUseCase $createProductUseCase,
        private UpdateProductUseCase $updateProductUseCase,
        private DeleteProductUseCase $deleteProductUseCase,
        private ListProductsUseCase $listProductsUseCase,
        private FindProductsByCategoryUseCase $findProductsByCategoryUseCase
    ) {
    }

    /**
     * Lista todos os produtos
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $products = $this->listProductsUseCase->execute();

            return new JsonResponse([
                'status' => 'success',
                'data' => array_map([$this, 'formatProductResponse'], $products)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo produto
     * 
     * @param CreateProductRequest $request
     * @return JsonResponse
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        try {
            $dto = new CreateProductDTO(
                $request->input('name'),
                (float) $request->input('price'),
                $request->input('category_id'),
                $request->input('description')
            );

            $product = $this->createProductUseCase->execute($dto);

            return new JsonResponse([
                'status' => 'success',
                'data' => $this->formatProductResponse($product)
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Atualiza um produto existente
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdateProductDTO(
                $id,
                $request->input('name'),
                (float) $request->input('price'),
                $request->input('category_id'),
                $request->input('description')
            );

            $product = $this->updateProductUseCase->execute($dto);

            return new JsonResponse([
                'status' => 'success',
                'data' => $this->formatProductResponse($product)
            ], 200);
        } catch (\InvalidArgumentException $e) {
            $statusCode = str_contains($e->getMessage(), 'not found') ? 404 : 400;
            
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $statusCode);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove um produto
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->deleteProductUseCase->execute($id);

            if (!$deleted) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Failed to delete product'
                ], 500);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Busca produtos por categoria
     * 
     * @param string $categoryId
     * @return JsonResponse
     */
    public function findByCategory(string $categoryId): JsonResponse
    {
        try {
            $products = $this->findProductsByCategoryUseCase->execute($categoryId);

            return new JsonResponse([
                'status' => 'success',
                'data' => array_map([$this, 'formatProductResponse'], $products)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formata a resposta do produto para JSON
     * 
     * @param \App\Core\Domain\Entities\Product $product
     * @return array
     */
    private function formatProductResponse($product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category_id' => $product->getCategoryId(),
            'description' => $product->getDescription(),
            'created_at' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $product->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
