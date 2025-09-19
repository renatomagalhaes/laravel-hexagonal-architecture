<?php

namespace Tests\Unit\Core\Ports\Repositories;

use App\Core\Domain\Entities\Category;
use App\Core\Ports\Repositories\CategoryRepository;
use PHPUnit\Framework\TestCase;

/**
 * Teste da interface CategoryRepository seguindo TDD
 * 
 * Este teste define o comportamento esperado do contrato CategoryRepository
 * antes de implementá-lo, seguindo os princípios TDD.
 * 
 * @package Tests\Unit\Core\Ports\Repositories
 */
class CategoryRepositoryTest extends TestCase
{
    /**
     * Teste: Interface deve ter método save
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método save corretamente.
     */
    public function test_interface_should_have_save_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('save'));
        $this->assertTrue($reflection->getMethod('save')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findById
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método findById corretamente.
     */
    public function test_interface_should_have_find_by_id_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findById'));
        $this->assertTrue($reflection->getMethod('findById')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findAll
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método findAll corretamente.
     */
    public function test_interface_should_have_find_all_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findAll'));
        $this->assertTrue($reflection->getMethod('findAll')->isPublic());
    }

    /**
     * Teste: Interface deve ter método delete
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método delete corretamente.
     */
    public function test_interface_should_have_delete_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('delete'));
        $this->assertTrue($reflection->getMethod('delete')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findActive
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método findActive corretamente.
     */
    public function test_interface_should_have_find_active_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findActive'));
        $this->assertTrue($reflection->getMethod('findActive')->isPublic());
    }

    /**
     * Teste: Interface deve ter método findByName
     * 
     * Este teste verifica se a interface CategoryRepository
     * define o método findByName corretamente.
     */
    public function test_interface_should_have_find_by_name_method(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        
        // Act & Assert
        $this->assertTrue($reflection->hasMethod('findByName'));
        $this->assertTrue($reflection->getMethod('findByName')->isPublic());
    }

    /**
     * Teste: Método save deve retornar Category
     * 
     * Este teste verifica se o método save tem a assinatura correta.
     */
    public function test_save_method_should_return_category(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $saveMethod = $reflection->getMethod('save');
        
        // Act & Assert
        $this->assertEquals(Category::class, $saveMethod->getReturnType()->getName());
    }

    /**
     * Teste: Método findById deve retornar Category ou null
     * 
     * Este teste verifica se o método findById tem a assinatura correta.
     */
    public function test_find_by_id_method_should_return_category_or_null(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $findByIdMethod = $reflection->getMethod('findById');
        
        // Act & Assert
        $returnType = $findByIdMethod->getReturnType();
        $this->assertTrue($returnType->allowsNull());
        $this->assertEquals(Category::class, $returnType->getName());
    }

    /**
     * Teste: Método findAll deve retornar array de Categories
     * 
     * Este teste verifica se o método findAll tem a assinatura correta.
     */
    public function test_find_all_method_should_return_array_of_categories(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $findAllMethod = $reflection->getMethod('findAll');
        
        // Act & Assert
        $this->assertEquals('array', $findAllMethod->getReturnType()->getName());
    }

    /**
     * Teste: Método findActive deve retornar array de Categories
     * 
     * Este teste verifica se o método findActive tem a assinatura correta.
     */
    public function test_find_active_method_should_return_array_of_categories(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $findActiveMethod = $reflection->getMethod('findActive');
        
        // Act & Assert
        $this->assertEquals('array', $findActiveMethod->getReturnType()->getName());
    }

    /**
     * Teste: Método findByName deve retornar Category ou null
     * 
     * Este teste verifica se o método findByName tem a assinatura correta.
     */
    public function test_find_by_name_method_should_return_category_or_null(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $findByNameMethod = $reflection->getMethod('findByName');
        
        // Act & Assert
        $returnType = $findByNameMethod->getReturnType();
        $this->assertTrue($returnType->allowsNull());
        $this->assertEquals(Category::class, $returnType->getName());
    }

    /**
     * Teste: Método delete deve retornar bool
     * 
     * Este teste verifica se o método delete tem a assinatura correta.
     */
    public function test_delete_method_should_return_bool(): void
    {
        // Arrange
        $reflection = new \ReflectionClass(CategoryRepository::class);
        $deleteMethod = $reflection->getMethod('delete');
        
        // Act & Assert
        $this->assertEquals('bool', $deleteMethod->getReturnType()->getName());
    }
}
