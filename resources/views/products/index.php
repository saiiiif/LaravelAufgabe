<div class="row">
    <div class="col-md-4 col-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="total-products"><?php echo (int) $totals['totalProducts']; ?></h3>
                <p>Total Products</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="/products/create" class="small-box-footer">Add new product <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="total-quantity"><?php echo (int) $totals['totalQuantity']; ?></h3>
                <p>Total Units in Stock</p>
            </div>
            <div class="icon">
                <i class="fas fa-pallet"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="total-value">€<?php echo number_format((float) $totals['totalValue'], 2, ',', '.'); ?></h3>
                <p>Total Inventory Value</p>
            </div>
            <div class="icon">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-circle mr-2"></i>Low stock alerts</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>SKU</th>
                                <th class="text-right">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="low-stock-table">
                            <?php if (empty($lowStock)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Great job! No low stock items right now.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lowStock as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['sku']); ?></td>
                                        <td class="text-right"><span class="badge badge-danger"><?php echo (int) $item['quantity']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Live stock overview</h3>
                <div class="card-tools">
                    <span class="badge badge-info" id="last-updated">Updating...</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 stock-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>SKU</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Value</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No products yet. Use the button above to add your first item.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                        <td class="text-right"><?php echo (int) $product['quantity']; ?></td>
                                        <td class="text-right">€<?php echo number_format((float) $product['unit_price'], 2, ',', '.'); ?></td>
                                        <td class="text-right">€<?php echo number_format((int) $product['quantity'] * (float) $product['unit_price'], 2, ',', '.'); ?></td>
                                        <td class="text-right">
                                            <a href="/products/<?php echo $product['id']; ?>/edit" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                            <form method="post" action="/products/<?php echo $product['id']; ?>/delete" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formatCurrency = (value) => {
            return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
        };

        const updateDashboard = (payload) => {
            document.getElementById('total-products').textContent = payload.totals.totalProducts;
            document.getElementById('total-quantity').textContent = payload.totals.totalQuantity;
            document.getElementById('total-value').textContent = formatCurrency(payload.totals.totalValue);

            const tableBody = document.getElementById('stock-table-body');
            tableBody.innerHTML = '';

            if (!payload.products.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No products yet. Use the button above to add your first item.</td></tr>';
            } else {
                payload.products.forEach((product) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td class="text-right">${product.quantity}</td>
                        <td class="text-right">${formatCurrency(product.unit_price)}</td>
                        <td class="text-right">${formatCurrency(product.quantity * product.unit_price)}</td>
                        <td class="text-right">
                            <a href="/products/${product.id}/edit" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form method="post" action="/products/${product.id}/delete" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            const lowStockTable = document.getElementById('low-stock-table');
            if (lowStockTable) {
                lowStockTable.innerHTML = '';
                if (!payload.lowStock.length) {
                    lowStockTable.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Great job! No low stock items right now.</td></tr>';
                } else {
                    payload.lowStock.forEach((item) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td>${item.sku}</td>
                            <td class="text-right"><span class="badge badge-danger">${item.quantity}</span></td>
                        `;
                        lowStockTable.appendChild(row);
                    });
                }
            }

            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated) {
                const now = new Date();
                lastUpdated.textContent = `Updated ${now.toLocaleTimeString()}`;
            }
        };

        const fetchStock = async () => {
            try {
                const response = await fetch('/api/stock', { headers: { 'Accept': 'application/json' } });
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const payload = await response.json();
                updateDashboard(payload);
            } catch (error) {
                console.error('Unable to refresh stock data:', error);
            }
        };

        fetchStock();
        setInterval(fetchStock, 5000);
    });
</script>
