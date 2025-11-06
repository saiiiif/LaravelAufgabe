<?php

namespace App\Http\Controllers;

use App\Models\ProductRepository;

class ProductController extends Controller
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function index(): void
    {
        $products = $this->repository->all();
        $totalQuantity = array_reduce($products, fn ($carry, $product) => $carry + (int) $product['quantity'], 0);
        $totalValue = array_reduce($products, fn ($carry, $product) => $carry + ((int) $product['quantity'] * (float) $product['unit_price']), 0.0);

        $this->view('products.index', [
            'products' => $products,
            'totalQuantity' => $totalQuantity,
            'totalValue' => $totalValue,
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function create(): void
    {
        $this->view('products.create', [
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function store(): void
    {
        $data = $this->validatedData();

        if (!$data) {
            $this->redirect('/products/create');
            return;
        }

        $this->repository->create($data);
        $_SESSION['flash'] = 'Product created successfully.';
        $this->redirect('/');
    }

    public function edit(string $id): void
    {
        $product = $this->repository->find($id);

        if ($product === null) {
            http_response_code(404);
            echo 'Product not found';
            return;
        }

        $this->view('products.edit', [
            'product' => $product,
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function update(string $id): void
    {
        $data = $this->validatedData();

        if (!$data) {
            $this->redirect("/products/{$id}/edit");
            return;
        }

        $this->repository->update($id, $data);
        $_SESSION['flash'] = 'Product updated successfully.';
        $this->redirect('/');
    }

    public function destroy(string $id): void
    {
        $this->repository->delete($id);
        $_SESSION['flash'] = 'Product deleted.';
        $this->redirect('/');
    }

    /**
     * @return array<string, string|int|float>|null
     */
    private function validatedData(): ?array
    {
        $name = trim($_POST['name'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $quantity = $_POST['quantity'] ?? '';
        $unitPrice = $_POST['unit_price'] ?? '';

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($sku === '') {
            $errors['sku'] = 'SKU is required.';
        }

        if (!is_numeric($quantity) || (int) $quantity < 0) {
            $errors['quantity'] = 'Quantity must be a non-negative number.';
        }

        if (!is_numeric($unitPrice) || (float) $unitPrice < 0) {
            $errors['unit_price'] = 'Unit price must be a non-negative number.';
        }

        if ($errors !== []) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = [
                'name' => $name,
                'sku' => $sku,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ];

            return null;
        }

        return [
            'name' => $name,
            'sku' => $sku,
            'quantity' => (int) $quantity,
            'unit_price' => (float) $unitPrice,
        ];
    }
}
