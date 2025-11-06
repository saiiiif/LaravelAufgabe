<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body { padding: 2rem; }
        .flash { background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .errors { background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; }
        table { width: 100%; }
    </style>
</head>
<body>
<main class="container">
    <header>
        <h1>Stock Manager</h1>
        <p class="muted">Keep track of your inventory with a lightweight Laravel-inspired application.</p>
    </header>

    <?php if (!empty($flash)): ?>
        <div class="flash"><?php echo htmlspecialchars($flash); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <strong>We could not save your changes:</strong>
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php include $viewPath; ?>
</main>
</body>
</html>
