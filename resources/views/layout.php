<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(($pageTitle ?? 'Stock Manager') . ' | Stock Manager'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .content-wrapper {
            min-height: 100vh;
        }
        .stock-table td, .stock-table th {
            vertical-align: middle;
        }
        .stock-table td.text-right, .stock-table th.text-right {
            text-align: right;
        }
    </style>
</head>
<body class="<?php echo !empty($authPage) ? 'hold-transition login-page' : 'hold-transition sidebar-mini layout-fixed'; ?>">
<?php if (!empty($authPage)): ?>
    <?php include $viewPath; ?>
<?php else: ?>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if (!empty($user)): ?>
                    <li class="nav-item d-none d-sm-inline-block">
                        <span class="nav-link"><i class="far fa-user-circle mr-1"></i><?php echo htmlspecialchars($user['name']); ?></span>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <form method="post" action="/logout" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Sign out</button>
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/dashboard" class="brand-link">
                <span class="brand-text font-weight-light">Stock Manager</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-warehouse text-white fa-2x"></i>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo htmlspecialchars($user['name'] ?? ''); ?></a>
                        <small class="text-muted"><?php echo htmlspecialchars($user['email'] ?? ''); ?></small>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link <?php echo ($_SERVER['REQUEST_URI'] ?? '') === '/dashboard' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/products/create" class="nav-link <?php echo ($_SERVER['REQUEST_URI'] ?? '') === '/products/create' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Add product</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><?php echo htmlspecialchars($pageTitle ?? ''); ?></h1>
                        </div>
                    </div>
                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($flash); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="mb-2"><i class="icon fas fa-exclamation-triangle mr-2"></i>We could not save your changes</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $message): ?>
                                    <li><?php echo htmlspecialchars($message); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <?php include $viewPath; ?>
                </div>
            </section>
        </div>

        <footer class="main-footer text-sm">
            <strong>&copy; <?php echo date('Y'); ?> Stock Manager.</strong> Keep your inventory up to date.
        </footer>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
