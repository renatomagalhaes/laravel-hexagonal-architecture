<?php

namespace App\Core\Application\DTOs;

/**
 * DTO (Data Transfer Object) para criação de categorias
 * 
 * DTOs são objetos simples que transportam dados entre camadas
 * sem lógica de negócio, seguindo os princípios da arquitetura hexagonal.
 * 
 * @package App\Core\Application\DTOs
 */
class CreateCategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description
    ) {
    }
}
