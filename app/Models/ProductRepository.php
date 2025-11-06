<?php

namespace App\Models;

use RuntimeException;

class ProductRepository
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
        $this->ensureStorageExists();
    }

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        return array_values($this->read());
    }

    /** @return array<string, mixed>|null */
    public function find(string $id): ?array
    {
        $products = $this->read();
        return $products[$id] ?? null;
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): array
    {
        $products = $this->read();
        $id = $this->generateId($products);
        $attributes['id'] = $id;
        $products[(string) $id] = $attributes;
        $this->write($products);

        return $attributes;
    }

    /** @param array<string, mixed> $attributes */
    public function update(string $id, array $attributes): array
    {
        $products = $this->read();

        if (!isset($products[$id])) {
            throw new RuntimeException('Product not found.');
        }

        $attributes['id'] = (int) $id;
        $products[$id] = $attributes;
        $this->write($products);

        return $attributes;
    }

    public function delete(string $id): void
    {
        $products = $this->read();
        unset($products[$id]);
        $this->write($products);
    }

    /**
     * @param array<int|string, array<string, mixed>> $products
     */
    private function generateId(array $products): int
    {
        $ids = array_map(static fn ($product) => (int) ($product['id'] ?? 0), $products);
        $ids[] = 0;
        return max($ids) + 1;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function read(): array
    {
        $contents = file_get_contents($this->storagePath);

        if ($contents === false) {
            return [];
        }

        $decoded = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    /**
     * @param array<string, array<string, mixed>> $products
     */
    private function write(array $products): void
    {
        $encoded = json_encode($products, JSON_PRETTY_PRINT);

        if ($encoded === false) {
            throw new RuntimeException('Failed to encode products.');
        }

        if (file_put_contents($this->storagePath, $encoded) === false) {
            throw new RuntimeException('Failed to write products.');
        }
    }

    private function ensureStorageExists(): void
    {
        if (!file_exists($this->storagePath)) {
            $this->write([]);
        }
    }
}
