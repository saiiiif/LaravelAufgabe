<section>
    <h2>Add a new product</h2>
    <form method="post" action="/products">
        <label>
            Name
            <input type="text" name="name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
        </label>
        <label>
            SKU
            <input type="text" name="sku" value="<?php echo htmlspecialchars($old['sku'] ?? ''); ?>" required>
        </label>
        <label>
            Quantity
            <input type="number" name="quantity" min="0" value="<?php echo htmlspecialchars($old['quantity'] ?? '0'); ?>" required>
        </label>
        <label>
            Unit price
            <input type="number" step="0.01" min="0" name="unit_price" value="<?php echo htmlspecialchars($old['unit_price'] ?? '0'); ?>" required>
        </label>
        <div class="grid">
            <button type="submit">Save product</button>
            <a role="button" class="secondary" href="/">Cancel</a>
        </div>
    </form>
</section>
