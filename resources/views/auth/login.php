<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Stock</b>Manager</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to manage your inventory</p>
            <?php if (!empty($flash)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($flash); ?>
                </div>
            <?php endif; ?>
            <form method="post" action="/login">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <p class="text-danger text-sm mb-3"><?php echo htmlspecialchars($errors['email']); ?></p>
                <?php endif; ?>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <p class="text-danger text-sm mb-3"><?php echo htmlspecialchars($errors['password']); ?></p>
                <?php endif; ?>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-0 text-muted text-center text-sm">Demo credentials: admin@example.com / password</p>
        </div>
    </div>
</div>
