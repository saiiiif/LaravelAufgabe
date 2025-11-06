<?php

namespace App\Http\Controllers;

use App\Core\RedirectResponse;
use App\Core\View;

abstract class Controller
{
    protected function view(string $name, array $data = []): void
    {
        View::make($name, $data);
    }

    protected function redirect(string $path): void
    {
        RedirectResponse::to($path);
    }
}
