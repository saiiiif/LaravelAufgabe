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

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function ensureAuthenticated(): void
    {
        if (!$this->isAuthenticated()) {
            $_SESSION['flash'] = 'Please sign in to continue.';
            $this->redirect('/login');
        }
    }

    /**
     * @return array{name: string, email: string}|null
     */
    protected function currentUser(): ?array
    {
        /** @var array{name: string, email: string}|null $user */
        $user = $_SESSION['user'] ?? null;

        return $user;
    }
}
