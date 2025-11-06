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
                                    <tr data-product-id="<?php echo htmlspecialchars((string) $product['id']); ?>">
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                        <td class="text-right"><?php echo (int) $product['quantity']; ?></td>
                                        <td class="text-right">€<?php echo number_format((float) $product['unit_price'], 2, ',', '.'); ?></td>
                                        <td class="text-right">€<?php echo number_format((int) $product['quantity'] * (float) $product['unit_price'], 2, ',', '.'); ?></td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-outline-secondary js-show-qr"
                                                data-toggle="modal"
                                                data-target="#qrModal"
                                                data-product-id="<?php echo htmlspecialchars((string) $product['id']); ?>"
                                                data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                data-product-sku="<?php echo htmlspecialchars($product['sku']); ?>"
                                                data-product-quantity="<?php echo (int) $product['quantity']; ?>"
                                                data-product-unit-price="<?php echo htmlspecialchars((string) $product['unit_price']); ?>"
                                                data-product-qr-token="<?php echo htmlspecialchars($product['qr_token']); ?>"
                                            >
                                                <i class="fas fa-qrcode"></i>
                                            </button>
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

<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">Product QR code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <canvas id="qrCanvas" class="mx-auto"></canvas>
                <h5 class="mt-3" id="qrProductTitle"></h5>
                <p class="text-muted" id="qrProductDetails"></p>
            </div>
            <div class="modal-footer">
                <a href="#" id="qrDownload" class="btn btn-outline-primary" download="product-qr.png">
                    <i class="fas fa-download mr-2"></i>Download QR code
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formatCurrency = (value) => {
            return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(value);
        };

        const escapeHtml = (value) => {
            const stringValue = value === null || value === undefined ? '' : String(value);
            return stringValue
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        const buildRow = (product) => {
            const totalValue = product.quantity * product.unit_price;
            return `
                <tr data-product-id="${product.id}">
                    <td>${escapeHtml(product.name)}</td>
                    <td>${escapeHtml(product.sku)}</td>
                    <td class="text-right">${product.quantity}</td>
                    <td class="text-right">${formatCurrency(product.unit_price)}</td>
                    <td class="text-right">${formatCurrency(totalValue)}</td>
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-outline-secondary js-show-qr"
                            data-toggle="modal"
                            data-target="#qrModal"
                            data-product-id="${product.id}"
                            data-product-name="${escapeHtml(product.name)}"
                            data-product-sku="${escapeHtml(product.sku)}"
                            data-product-quantity="${product.quantity}"
                            data-product-unit-price="${product.unit_price}"
                            data-product-qr-token="${product.qr_token}"
                        >
                            <i class="fas fa-qrcode"></i>
                        </button>
                        <a href="/products/${product.id}/edit" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form method="post" action="/products/${product.id}/delete" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            `;
        };

        const buildLowStockRow = (item) => {
            return `
                <tr>
                    <td>${escapeHtml(item.name)}</td>
                    <td>${escapeHtml(item.sku)}</td>
                    <td class="text-right"><span class="badge badge-danger">${item.quantity}</span></td>
                </tr>
            `;
        };

        const normaliseProduct = (product) => {
            const quantity = Number.parseInt(product.quantity ?? '0', 10);
            const unitPrice = Number.parseFloat(product.unit_price ?? '0');
            return {
                ...product,
                quantity: Number.isNaN(quantity) ? 0 : quantity,
                unit_price: Number.isNaN(unitPrice) ? 0 : unitPrice,
            };
        };

        const normaliseLowStockItem = (item) => {
            const quantity = Number.parseInt(item.quantity ?? '0', 10);
            return {
                ...item,
                quantity: Number.isNaN(quantity) ? 0 : quantity,
            };
        };

        const qrCanvas = document.getElementById('qrCanvas');
        const qrTitle = document.getElementById('qrProductTitle');
        const qrDetails = document.getElementById('qrProductDetails');
        const qrDownload = document.getElementById('qrDownload');

        const renderQr = async (product) => {
            const payload = JSON.stringify({
                id: product.id,
                name: product.name,
                sku: product.sku,
                quantity: product.quantity,
                unit_price: product.unit_price,
                qr_token: product.qr_token,
            });

            await QRCode.toCanvas(qrCanvas, payload, {
                width: 240,
                margin: 2,
                color: {
                    dark: '#212529',
                    light: '#ffffff',
                },
            });

            qrTitle.textContent = product.name;
            qrDetails.textContent = `SKU ${product.sku} • ${product.quantity} units at ${formatCurrency(product.unit_price)}`;
            qrDownload.href = qrCanvas.toDataURL('image/png');
            const safeSku = product.sku.replace(/[^a-z0-9-_]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            qrDownload.download = `${safeSku || 'product'}-qr.png`;
        };

        const bindQrButtons = () => {
            document.querySelectorAll('.js-show-qr').forEach((button) => {
                if (button.dataset.bound === 'true') {
                    return;
                }

                button.addEventListener('click', () => {
                    const quantity = Number.parseInt(button.dataset.productQuantity ?? '0', 10);
                    const unitPrice = Number.parseFloat(button.dataset.productUnitPrice ?? '0');
                    const product = {
                        id: Number.parseInt(button.dataset.productId ?? '0', 10) || 0,
                        name: button.dataset.productName ?? '',
                        sku: button.dataset.productSku ?? '',
                        quantity: Number.isNaN(quantity) ? 0 : quantity,
                        unit_price: Number.isNaN(unitPrice) ? 0 : unitPrice,
                        qr_token: button.dataset.productQrToken ?? '',
                    };

                    renderQr(product).catch((error) => {
                        console.error('Unable to generate QR code', error);
                    });
                });

                button.dataset.bound = 'true';
            });
        };

        const updateDashboard = (payload) => {
            const totals = payload.totals ?? { totalProducts: 0, totalQuantity: 0, totalValue: 0 };
            const totalProducts = Number.parseInt(totals.totalProducts ?? '0', 10);
            const totalQuantity = Number.parseInt(totals.totalQuantity ?? '0', 10);
            const totalValue = Number.parseFloat(totals.totalValue ?? '0');

            document.getElementById('total-products').textContent = Number.isNaN(totalProducts) ? 0 : totalProducts;
            document.getElementById('total-quantity').textContent = Number.isNaN(totalQuantity) ? 0 : totalQuantity;
            document.getElementById('total-value').textContent = formatCurrency(Number.isNaN(totalValue) ? 0 : totalValue);

            const tableBody = document.getElementById('stock-table-body');
            tableBody.innerHTML = '';

            if (!payload.products.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No products yet. Use the button above to add your first item.</td></tr>';
            } else {
                payload.products.forEach((product) => {
                    tableBody.insertAdjacentHTML('beforeend', buildRow(normaliseProduct(product)));
                });
            }

            const lowStockTable = document.getElementById('low-stock-table');
            if (lowStockTable) {
                lowStockTable.innerHTML = '';
                if (!payload.lowStock.length) {
                    lowStockTable.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Great job! No low stock items right now.</td></tr>';
                } else {
                    payload.lowStock.forEach((item) => {
                        lowStockTable.insertAdjacentHTML('beforeend', buildLowStockRow(normaliseLowStockItem(item)));
                    });
                }
            }

            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated) {
                const now = new Date();
                lastUpdated.textContent = `Updated ${now.toLocaleTimeString()}`;
            }

            bindQrButtons();
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

        bindQrButtons();
        fetchStock();
        setInterval(fetchStock, 5000);
    });
</script>
