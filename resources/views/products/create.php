<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add a new product</h3>
            </div>
            <form method="post" action="/products">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" id="sku" name="sku" class="form-control" value="<?php echo htmlspecialchars($old['sku'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="0" class="form-control" value="<?php echo htmlspecialchars($old['quantity'] ?? '0'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_price">Unit price (€)</label>
                        <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" class="form-control" value="<?php echo htmlspecialchars($old['unit_price'] ?? '0'); ?>" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save product</button>
                    <a href="/dashboard" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
