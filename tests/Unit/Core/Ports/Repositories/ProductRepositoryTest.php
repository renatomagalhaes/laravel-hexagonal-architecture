<?php

namespace Tests\Unit\Core\Ports\Repositories;

use App\Core\Domain\Entities\Product;
use App\Core\Ports\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;

/**
 * Teste da interface ProductRepository seguindo TDD
 * 
 * Este teste define o comportamento esperado do contrato ProductRepository
 * antes de implementá-lo, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Ports\Repositories
 */
class ProductRepositoryTest extends TestCase
{
    /**
     * Teste: Interface deve ter método save
     * 
     * Este teste verifica se a interface ProductRepository
     * define o método save corretamente.
     */
    public function test_interface_should_have_save_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('save'));
        $this->assertTrue($reflection->getMethod('save')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findById
     * 
     * Este teste verifica se a interface ProductRepository
     * define o método findById corretamente.
     */
    public function test_interface_should_have_find_by_id_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findById'));
        $this->assertTrue($reflection->getMethod('findById')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findAll
     * 
     * Este teste verifica se a interface ProductRepository
     * define o método findAll corretamente.
     */
    public function test_interface_should_have_find_all_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findAll'));
        $this->assertTrue($reflection->getMethod('findAll')->isPublic());
    }

    /**
     * Teste: Interface deve ter método delete
     * 
     * Este teste verifica se a interface ProductRepository
     * define o método delete corretamente.
     */
    public function test_interface_should_have_delete_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('delete'));
        $this->assertTrue($reflection->getMethod('delete')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findByCategoryId
     * 
     * Este teste verifica se a interface ProductRepository
     * define o método findByCategoryId corretamente.
     */
    public function test_interface_should_have_find_by_category_id_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findByCategoryId'));
        $this->assertTrue($reflection->getMethod('findByCategoryId')->isPublic());
    }

    /**
     * Teste: Método save deve retornar Product
     * 
     * Este teste verifica se o método save tem a assinatura correta.
     */
    public function test_save_method_should_return_product(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        $saveMethod = $reflection->getMethod('save');
        
        // Act & Assert
        $this->assertEquals(Product::class, $saveMethod->getReturnType()->getName());
    }

    /**
     * Teste: Método findById deve retornar Product ou null
     * 
     * Este teste verifica se o método findById tem a assinatura correta.
     */
    public function test_find_by_id_method_should_return_product_or_null(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        $findByIdMethod = $reflection->getMethod('findById');
        
        // Act & Assert
        $returnType = $findByIdMethod->getReturnType();
        $this->assertTrue($returnType->allowsNull());
        $this->assertEquals(Product::class, $returnType->getName());
    }

    /**
     * Teste: Método findAll deve retornar array de Products
     * 
     * Este teste verifica se o método findAll tem a assinatura correta.
     */
    public function test_find_all_method_should_return_array_of_products(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(ProductRepository::class);
        $findAllMethod = $reflection->getMethod('findAll');
        
        // Act & Assert
        $this->assertEquals('array', $findAllMethod->getReturnType()->getName());
    }
}
