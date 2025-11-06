<?php

namespace App\Models;

use PDO;
use RuntimeException;

class ProductRepository
{
    public function __construct(private PDO $connection)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $statement = $this->connection->query('SELECT * FROM products ORDER BY created_at DESC');
        $rows = $statement !== false ? $statement->fetchAll() : [];

        return array_map(fn (array $row) => $this->mapProduct($row), $rows);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : $this->mapProduct($row);
    }

    /**
     * @param array{name: string, sku: string, quantity: int, unit_price: float} $attributes
     * @return array<string, mixed>
     */
    public function create(array $attributes): array
    {
        $now = $this->now();
        $token = $this->generateToken();

        $statement = $this->connection->prepare(
            'INSERT INTO products (name, sku, quantity, unit_price, qr_token, created_at, updated_at)
             VALUES (:name, :sku, :quantity, :unit_price, :qr_token, :created_at, :updated_at)'
        );

        $statement->execute([
            'name' => $attributes['name'],
            'sku' => $attributes['sku'],
            'quantity' => $attributes['quantity'],
            'unit_price' => $attributes['unit_price'],
            'qr_token' => $token,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $id = $this->connection->lastInsertId();

        if ($id === false) {
            throw new RuntimeException('Failed to determine the inserted product identifier.');
        }

        return $this->requireProduct($id);
    }

    /**
     * @param array{name: string, sku: string, quantity: int, unit_price: float} $attributes
     * @return array<string, mixed>
     */
    public function update(string $id, array $attributes): array
    {
        $this->requireProduct($id);

        $statement = $this->connection->prepare(
            'UPDATE products SET name = :name, sku = :sku, quantity = :quantity, unit_price = :unit_price, updated_at = :updated_at WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'name' => $attributes['name'],
            'sku' => $attributes['sku'],
            'quantity' => $attributes['quantity'],
            'unit_price' => $attributes['unit_price'],
            'updated_at' => $this->now(),
        ]);

        return $this->requireProduct($id);
    }

    public function delete(string $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    private function requireProduct(string $id): array
    {
        $product = $this->find($id);

        if ($product === null) {
            throw new RuntimeException('Product not found.');
        }

        return $product;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function mapProduct(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'sku' => (string) $row['sku'],
            'quantity' => (int) $row['quantity'],
            'unit_price' => (float) $row['unit_price'],
            'qr_token' => (string) $row['qr_token'],
            'created_at' => (string) $row['created_at'],
            'updated_at' => (string) $row['updated_at'],
        ];
    }

    private function now(): string
    {
        return gmdate('c');
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
