<?php

namespace App\DTOs\Category;

class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? str($data['name'])->slug(),
            description: $data['description'] ?? null,
        );
    }
}
