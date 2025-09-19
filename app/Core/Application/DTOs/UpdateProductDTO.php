<?php

namespace App\Core\Application\DTOs;

/**
 * DTO para atualização de produto
 * 
 * Data Transfer Object que encapsula os dados necessários
 * para atualizar um produto existente.
 * 
 * @package App\Core\Application\DTOs
 */
class UpdateProductDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price,
        public readonly string $categoryId,
        public readonly string $description
    ) {
    }
}
