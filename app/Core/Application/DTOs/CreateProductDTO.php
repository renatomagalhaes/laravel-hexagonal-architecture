<?php

namespace App\Core\Application\DTOs;

/**
 * DTO (Data Transfer Object) para criação de produtos
 * 
 * DTOs são objetos simples que transportam dados entre camadas
 * sem lógica de negócio, seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\DTOs
 */
class CreateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly string $categoryId,
        public readonly string $description
    ) {
    }
}
