<?php

namespace App\DTOs\Category;

class UpdateCategoryDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'] ?? null,
            $data['slug'] ?? null,
            $data['description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
        ], fn($value) => $value !== null);
    }
}
