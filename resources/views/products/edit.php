<section>
    <h2>Edit product</h2>
    <form method="post" action="/products/<?php echo $product['id']; ?>">
        <input type="hidden" name="_method" value="PUT">
        <label>
            Name
            <input type="text" name="name" value="<?php echo htmlspecialchars($old['name'] ?? $product['name']); ?>" required>
        </label>
        <label>
            SKU
            <input type="text" name="sku" value="<?php echo htmlspecialchars($old['sku'] ?? $product['sku']); ?>" required>
        </label>
        <label>
            Quantity
            <input type="number" name="quantity" min="0" value="<?php echo htmlspecialchars($old['quantity'] ?? $product['quantity']); ?>" required>
        </label>
        <label>
            Unit price
            <input type="number" step="0.01" min="0" name="unit_price" value="<?php echo htmlspecialchars($old['unit_price'] ?? $product['unit_price']); ?>" required>
        </label>
        <div class="grid">
            <button type="submit">Update product</button>
            <a role="button" class="secondary" href="/">Cancel</a>
        </div>
    </form>
</section>
