<?php

namespace App\Providers;

use App\Core\Application\UseCases\CreateCategoryUseCase;
use App\Core\Application\UseCases\CreateProductUseCase;
use App\Core\Application\UseCases\DeleteProductUseCase;
use App\Core\Application\UseCases\FindProductsByCategoryUseCase;
use App\Core\Application\UseCases\ListProductsUseCase;
use App\Core\Application\UseCases\UpdateProductUseCase;
use App\Core\Infrastructure\Database\InMemoryCategoryRepository;
use App\Core\Infrastructure\Database\InMemoryProductRepository;
use App\Core\Ports\Repositories\CategoryRepository;
use App\Core\Ports\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para registrar dependências do Core
 * 
 * Responsável por registrar as implementações dos repositórios
 * e use cases no container de injeção de dependência do Laravel.
 * 
 * @package App\Providers
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar repositórios
        $this->app->bind(ProductRepository::class, InMemoryProductRepository::class);
        $this->app->bind(CategoryRepository::class, InMemoryCategoryRepository::class);

        // Registrar Use Cases
        $this->app->bind(CreateCategoryUseCase::class, function ($app) {
            return new CreateCategoryUseCase(
                $app->make(CategoryRepository::class)
            );
        });

        $this->app->bind(CreateProductUseCase::class, function ($app) {
            return new CreateProductUseCase(
                $app->make(ProductRepository::class)
            );
        });

        $this->app->bind(UpdateProductUseCase::class, function ($app) {
            return new UpdateProductUseCase(
                $app->make(ProductRepository::class)
            );
        });

        $this->app->bind(DeleteProductUseCase::class, function ($app) {
            return new DeleteProductUseCase(
                $app->make(ProductRepository::class)
            );
        });

        $this->app->bind(ListProductsUseCase::class, function ($app) {
            return new ListProductsUseCase(
                $app->make(ProductRepository::class)
            );
        });

        $this->app->bind(FindProductsByCategoryUseCase::class, function ($app) {
            return new FindProductsByCategoryUseCase(
                $app->make(ProductRepository::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
