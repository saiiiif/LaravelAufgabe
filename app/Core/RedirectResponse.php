<?php

namespace App\Core;

class RedirectResponse
{
    public static function to(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
