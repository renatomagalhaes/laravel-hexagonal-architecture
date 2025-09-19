<?php

namespace App\Core\Domain\Services;

use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\CategoryRepository;
use App\Core\Ports\Repositories\ProductRepository;

/**
 * Domain Service para regras de negócio complexas relacionadas a produtos
 * 
 * Domain Services encapsulam regras de negócio que não pertencem
 * naturalmente a uma única entidade, mas envolvem múltiplas entidades
 * ou regras complexas do domínio.
 * 
 * @package App\Core\Domain\Services
 */
class ProductDomainService
{
    // Faixas de preço aceitáveis por categoria (regra de negócio)
    private const PRICE_RANGES = [
        'category_1' => ['min' => 10.00, 'max' => 1000.00],
        'category_2' => ['min' => 50.00, 'max' => 2000.00],
        'category_3' => ['min' => 100.00, 'max' => 5000.00],
        'default' => ['min' => 1.00, 'max' => 10000.00],
    ];

    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Verifica se uma categoria está ativa
     * 
     * @param string $categoryId ID da categoria
     * @return bool True se a categoria estiver ativa, false caso contrário
     */
    public function isCategoryActive(string $categoryId): bool
    {
        $category = $this->categoryRepository->findById($categoryId);
        
        if (!$category) {
            return false;
        }

        return $category->isActive();
    }

    /**
     * Verifica se o nome do produto é único dentro da categoria
     * 
     * @param string $productName Nome do produto
     * @param string $categoryId ID da categoria
     * @return bool True se o nome for único, false caso contrário
     */
    public function isProductNameUnique(string $productName, string $categoryId): bool
    {
        $products = $this->productRepository->findAll();
        
        foreach ($products as $product) {
            if ($product->getName() === $productName && $product->getCategoryId() === $categoryId) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se o preço está dentro da faixa aceitável para a categoria
     * 
     * @param float $price Preço do produto
     * @param string $categoryId ID da categoria
     * @return bool True se o preço estiver na faixa aceitável, false caso contrário
     */
    public function isPriceWithinAcceptableRange(float $price, string $categoryId): bool
    {
        $category = $this->categoryRepository->findById($categoryId);
        
        if (!$category) {
            return false;
        }

        $priceRange = self::PRICE_RANGES[$categoryId] ?? self::PRICE_RANGES['default'];
        
        return $price >= $priceRange['min'] && $price <= $priceRange['max'];
    }

    /**
     * Verifica se um produto pode ser criado (validação combinada)
     * 
     * @param string $productName Nome do produto
     * @param float $price Preço do produto
     * @param string $categoryId ID da categoria
     * @return bool True se o produto pode ser criado, false caso contrário
     */
    public function canCreateProduct(string $productName, float $price, string $categoryId): bool
    {
        // Verifica se a categoria está ativa
        if (!$this->isCategoryActive($categoryId)) {
            return false;
        }

        // Verifica se o nome é único
        if (!$this->isProductNameUnique($productName, $categoryId)) {
            return false;
        }

        // Verifica se o preço está na faixa aceitável
        if (!$this->isPriceWithinAcceptableRange($price, $categoryId)) {
            return false;
        }

        return true;
    }

    /**
     * Obtém a faixa de preço aceitável para uma categoria
     * 
     * @param string $categoryId ID da categoria
     * @return array Faixa de preço ['min' => float, 'max' => float]
     */
    public function getPriceRangeForCategory(string $categoryId): array
    {
        return self::PRICE_RANGES[$categoryId] ?? self::PRICE_RANGES['default'];
    }

    /**
     * Calcula o preço médio dos produtos de uma categoria
     * 
     * @param string $categoryId ID da categoria
     * @return float|null Preço médio ou null se não houver produtos
     */
    public function getAveragePriceForCategory(string $categoryId): ?float
    {
        $products = $this->productRepository->findByCategoryId($categoryId);
        
        if (empty($products)) {
            return null;
        }

        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPrice();
        }

        return $totalPrice / count($products);
    }

    /**
     * Verifica se um produto está com preço competitivo em relação aos outros da categoria
     * 
     * @param float $price Preço do produto
     * @param string $categoryId ID da categoria
     * @return bool True se o preço for competitivo, false caso contrário
     */
    public function isPriceCompetitive(float $price, string $categoryId): bool
    {
        $averagePrice = $this->getAveragePriceForCategory($categoryId);
        
        if ($averagePrice === null) {
            return true; // Se não há outros produtos, considera competitivo
        }

        // Considera competitivo se estiver dentro de 20% da média
        $tolerance = $averagePrice * 0.2;
        
        return $price >= ($averagePrice - $tolerance) && $price <= ($averagePrice + $tolerance);
    }
}
