<?php

namespace App\Core;

class View
{
    public static function make(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'View not found.';
            return;
        }

        include __DIR__ . '/../../resources/views/layout.php';
    }
}
