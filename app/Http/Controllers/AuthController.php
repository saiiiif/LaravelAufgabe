<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    private const DEMO_USER = [
        'email' => 'admin@example.com',
        'password' => 'password',
        'name' => 'Admin User',
    ];

    public function showLogin(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth.login', [
            'flash' => $_SESSION['flash'] ?? null,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'authPage' => true,
            'pageTitle' => 'Sign in',
        ]);

        unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
    }

    public function login(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if ($errors !== []) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['email' => $email];
            $this->redirect('/login');
        }

        if ($email !== self::DEMO_USER['email'] || $password !== self::DEMO_USER['password']) {
            $_SESSION['flash'] = 'The provided credentials do not match our records.';
            $_SESSION['old'] = ['email' => $email];
            $this->redirect('/login');
        }

        $_SESSION['user'] = [
            'email' => self::DEMO_USER['email'],
            'name' => self::DEMO_USER['name'],
        ];

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        $_SESSION['flash'] = 'You have been signed out.';
        $this->redirect('/login');
    }
}
