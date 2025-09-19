<?php

namespace Tests\Unit\Core\Domain\Services;

use App\Core\Domain\Entities\Category;
use App\Core\Domain\Entities\Product;
use App\Core\Domain\Services\ProductDomainService;
use App\Core\Ports\Repositories\CategoryRepository;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Teste do Domain Service ProductDomainService seguindo TDD
 * 
 * Domain Services encapsulam regras de negócio complexas que
 * não pertencem naturalmente a uma única entidade.
 * 
 * @package Tests\Unit\Core\Domain\Services
 */
class ProductDomainServiceTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private CategoryRepository|MockObject $categoryRepository;
    private ProductDomainService $productDomainService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->productDomainService = new ProductDomainService(
            $this->productRepository,
            $this->categoryRepository
        );
    }

    /**
     * Teste: Deve validar se categoria está ativa
     */
    public function test_should_validate_category_is_active(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        // Act
        $result = $this->productDomainService->isCategoryActive($categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não está ativa
     */
    public function test_should_return_false_when_category_is_inactive(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $inactiveCategory = new Category('Categoria Inativa', 'Descrição', $categoryId);
        $inactiveCategory->deactivate();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($inactiveCategory);

        // Act
        $result = $this->productDomainService->isCategoryActive($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não existe
     */
    public function test_should_return_false_when_category_does_not_exist(): void
    {
        // Arrange
        $categoryId = 'category_inexistente';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn(null);

        // Act
        $result = $this->productDomainService->isCategoryActive($categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se nome do produto é único
     */
    public function test_should_validate_product_name_is_unique(): void
    {
        // Arrange
        $productName = 'Produto Único';
        $categoryId = 'category_1';

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $result = $this->productDomainService->isProductNameUnique($productName, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando nome do produto já existe
     */
    public function test_should_return_false_when_product_name_already_exists(): void
    {
        // Arrange
        $productName = 'Produto Existente';
        $categoryId = 'category_1';
        
        $existingProduct = new Product(
            $productName,
            100.00,
            $categoryId,
            'Descrição',
            'product_1'
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$existingProduct]);

        // Act
        $result = $this->productDomainService->isProductNameUnique($productName, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve permitir nome duplicado em categorias diferentes
     */
    public function test_should_allow_duplicate_name_in_different_categories(): void
    {
        // Arrange
        $productName = 'Produto Mesmo Nome';
        $categoryId = 'category_1';
        
        $existingProduct = new Product(
            $productName,
            100.00,
            'category_2', // Categoria diferente
            'Descrição',
            'product_1'
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$existingProduct]);

        // Act
        $result = $this->productDomainService->isProductNameUnique($productName, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve validar se preço está dentro da faixa aceitável para a categoria
     */
    public function test_should_validate_price_is_within_acceptable_range(): void
    {
        // Arrange
        $price = 100.00;
        $categoryId = 'category_1';
        $category = new Category('Categoria', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($category);

        // Act
        $result = $this->productDomainService->isPriceWithinAcceptableRange($price, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando preço está muito alto para a categoria
     */
    public function test_should_return_false_when_price_is_too_high_for_category(): void
    {
        // Arrange
        $price = 10000.00; // Preço muito alto
        $categoryId = 'category_1';
        $category = new Category('Categoria', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($category);

        // Act
        $result = $this->productDomainService->isPriceWithinAcceptableRange($price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não existe para validação de preço
     */
    public function test_should_return_false_when_category_does_not_exist_for_price_validation(): void
    {
        // Arrange
        $price = 100.00;
        $categoryId = 'category_inexistente';

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn(null);

        // Act
        $result = $this->productDomainService->isPriceWithinAcceptableRange($price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve validar se produto pode ser criado (regras combinadas)
     */
    public function test_should_validate_product_can_be_created(): void
    {
        // Arrange
        $productName = 'Produto Válido';
        $price = 100.00;
        $categoryId = 'category_1';
        
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->exactly(2)) // Será chamado 2 vezes: isCategoryActive e isPriceWithinAcceptableRange
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $result = $this->productDomainService->canCreateProduct($productName, $price, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando categoria não está ativa
     */
    public function test_should_return_false_when_category_is_inactive_for_creation(): void
    {
        // Arrange
        $productName = 'Produto Válido';
        $price = 100.00;
        $categoryId = 'category_1';
        
        $inactiveCategory = new Category('Categoria Inativa', 'Descrição', $categoryId);
        $inactiveCategory->deactivate();

        $this->categoryRepository
            ->expects($this->once())
            ->method('findById')
            ->with($categoryId)
            ->willReturn($inactiveCategory);

        // Act
        $result = $this->productDomainService->canCreateProduct($productName, $price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando nome do produto já existe para criação
     */
    public function test_should_return_false_when_product_name_exists_for_creation(): void
    {
        // Arrange
        $productName = 'Produto Existente';
        $price = 100.00;
        $categoryId = 'category_1';
        
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->once()) // Será chamado apenas 1 vez: isCategoryActive (não chega ao isPriceWithinAcceptableRange)
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        $existingProduct = new Product(
            $productName,
            100.00,
            $categoryId,
            'Descrição',
            'product_1'
        );

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$existingProduct]);

        // Act
        $result = $this->productDomainService->canCreateProduct($productName, $price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve retornar false quando preço está fora da faixa para criação
     */
    public function test_should_return_false_when_price_out_of_range_for_creation(): void
    {
        // Arrange
        $productName = 'Produto Válido';
        $price = 10000.00; // Preço muito alto
        $categoryId = 'category_1';
        
        $activeCategory = new Category('Categoria Ativa', 'Descrição', $categoryId);

        $this->categoryRepository
            ->expects($this->exactly(2)) // Será chamado 2 vezes: isCategoryActive e isPriceWithinAcceptableRange
            ->method('findById')
            ->with($categoryId)
            ->willReturn($activeCategory);

        $this->productRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Act
        $result = $this->productDomainService->canCreateProduct($productName, $price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste: Deve obter faixa de preço para categoria específica
     */
    public function test_should_get_price_range_for_specific_category(): void
    {
        // Arrange
        $categoryId = 'category_1';

        // Act
        $result = $this->productDomainService->getPriceRangeForCategory($categoryId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(10.00, $result['min']);
        $this->assertEquals(1000.00, $result['max']);
    }

    /**
     * Teste: Deve obter faixa de preço padrão para categoria não mapeada
     */
    public function test_should_get_default_price_range_for_unmapped_category(): void
    {
        // Arrange
        $categoryId = 'category_unknown';

        // Act
        $result = $this->productDomainService->getPriceRangeForCategory($categoryId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(1.00, $result['min']);
        $this->assertEquals(10000.00, $result['max']);
    }

    /**
     * Teste: Deve calcular preço médio para categoria com produtos
     */
    public function test_should_calculate_average_price_for_category_with_products(): void
    {
        // Arrange
        $categoryId = 'category_1';
        $products = [
            new Product('Produto 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
            new Product('Produto 2', 200.00, $categoryId, 'Descrição 2', 'product_2'),
            new Product('Produto 3', 300.00, $categoryId, 'Descrição 3', 'product_3'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $result = $this->productDomainService->getAveragePriceForCategory($categoryId);

        // Assert
        $this->assertEquals(200.00, $result);
    }

    /**
     * Teste: Deve retornar null quando categoria não tem produtos
     */
    public function test_should_return_null_when_category_has_no_products(): void
    {
        // Arrange
        $categoryId = 'category_1';

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn([]);

        // Act
        $result = $this->productDomainService->getAveragePriceForCategory($categoryId);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Teste: Deve validar se preço é competitivo quando não há outros produtos
     */
    public function test_should_validate_price_is_competitive_when_no_other_products(): void
    {
        // Arrange
        $price = 100.00;
        $categoryId = 'category_1';

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn([]);

        // Act
        $result = $this->productDomainService->isPriceCompetitive($price, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve validar se preço é competitivo quando está dentro da tolerância
     */
    public function test_should_validate_price_is_competitive_when_within_tolerance(): void
    {
        // Arrange
        $price = 120.00; // 20% acima da média (100)
        $categoryId = 'category_1';
        $products = [
            new Product('Produto 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $result = $this->productDomainService->isPriceCompetitive($price, $categoryId);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Teste: Deve retornar false quando preço está fora da tolerância
     */
    public function test_should_return_false_when_price_is_outside_tolerance(): void
    {
        // Arrange
        $price = 200.00; // 100% acima da média (100)
        $categoryId = 'category_1';
        $products = [
            new Product('Produto 1', 100.00, $categoryId, 'Descrição 1', 'product_1'),
        ];

        $this->productRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->with($categoryId)
            ->willReturn($products);

        // Act
        $result = $this->productDomainService->isPriceCompetitive($price, $categoryId);

        // Assert
        $this->assertFalse($result);
    }
}
