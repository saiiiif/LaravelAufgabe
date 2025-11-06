<section>
    <div class="grid">
        <article>
            <h2>Inventory Snapshot</h2>
            <p><strong>Total items:</strong> <?php echo count($products); ?></p>
            <p><strong>Total units in stock:</strong> <?php echo $totalQuantity; ?></p>
            <p><strong>Total inventory value:</strong> €<?php echo number_format($totalValue, 2); ?></p>
            <a role="button" href="/products/create">Add product</a>
        </article>
    </div>

    <?php if (empty($products)): ?>
        <article>
            <p class="muted">No products yet. Start by adding your first item.</p>
        </article>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit price</th>
                    <th class="text-right">Value</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                    <td class="text-right"><?php echo (int) $product['quantity']; ?></td>
                    <td class="text-right">€<?php echo number_format((float) $product['unit_price'], 2); ?></td>
                    <td class="text-right">€<?php echo number_format((int) $product['quantity'] * (float) $product['unit_price'], 2); ?></td>
                    <td>
                        <a href="/products/<?php echo $product['id']; ?>/edit">Edit</a>
                        <form method="post" action="/products/<?php echo $product['id']; ?>/delete" style="display:inline">
                            <button type="submit" class="secondary">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
