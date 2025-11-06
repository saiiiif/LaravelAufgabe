<?php

namespace App\Http\Controllers;

use App\Models\ProductRepository;

class ProductController extends Controller
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function dashboard(): void
    {
        $this->ensureAuthenticated();

        $data = $this->inventoryData();

        $this->view('products.index', $data + [
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'user' => $this->currentUser(),
            'pageTitle' => 'Dashboard',
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function stats(): void
    {
        $this->ensureAuthenticated();

        $data = $this->inventoryData();

        header('Content-Type: application/json');
        echo json_encode([
            'products' => array_values($data['products']),
            'totals' => $data['totals'],
            'lowStock' => $data['lowStock'],
        ]);
    }

    public function create(): void
    {
        $this->ensureAuthenticated();

        $this->view('products.create', [
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'user' => $this->currentUser(),
            'pageTitle' => 'Add product',
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function store(): void
    {
        $this->ensureAuthenticated();

        $data = $this->validatedData();

        if (!$data) {
            $this->redirect('/products/create');
        }

        $this->repository->create($data);
        $_SESSION['flash'] = 'Product created successfully.';
        $this->redirect('/dashboard');
    }

    public function edit(string $id): void
    {
        $this->ensureAuthenticated();

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
            'user' => $this->currentUser(),
            'pageTitle' => 'Edit product',
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function update(string $id): void
    {
        $this->ensureAuthenticated();

        $data = $this->validatedData();

        if (!$data) {
            $this->redirect("/products/{$id}/edit");
        }

        $this->repository->update($id, $data);
        $_SESSION['flash'] = 'Product updated successfully.';
        $this->redirect('/dashboard');
    }

    public function destroy(string $id): void
    {
        $this->ensureAuthenticated();

        $this->repository->delete($id);
        $_SESSION['flash'] = 'Product deleted.';
        $this->redirect('/dashboard');
    }

    /**
     * @return array{
     *     products: array<int, array<string, mixed>>,
     *     totals: array{totalProducts: int, totalQuantity: int, totalValue: float},
     *     lowStock: array<int, array<string, mixed>>
     * }
     */
    private function inventoryData(): array
    {
        $products = $this->repository->all();
        $totalQuantity = array_reduce($products, fn ($carry, $product) => $carry + (int) $product['quantity'], 0);
        $totalValue = array_reduce($products, fn ($carry, $product) => $carry + ((int) $product['quantity'] * (float) $product['unit_price']), 0.0);
        $lowStock = array_values(array_filter($products, fn ($product) => (int) $product['quantity'] <= 5));

        return [
            'products' => $products,
            'totals' => [
                'totalProducts' => count($products),
                'totalQuantity' => $totalQuantity,
                'totalValue' => $totalValue,
            ],
            'lowStock' => $lowStock,
        ];
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
