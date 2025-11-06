# Stock Manager

This repository contains a lightweight, self-contained inventory management application written in PHP. The structure mirrors a Laravel project so that routes, controllers, and views feel familiar, but it does not require any external dependencies which makes it easy to run in restricted environments.

## Features

- List products with calculated inventory totals (units in stock and inventory value)
- Create, edit, and delete products
- Basic validation with friendly feedback stored in session flashes
- JSON file persistence so the application can be used without a database server

## Getting started

1. Install PHP 8.2 or newer.
2. Install Composer (only needed for autoload generation).
3. Install the Composer autoloader:

   ```bash
   composer dump-autoload
   ```

4. Start the development server:

   ```bash
   composer serve
   ```

5. Visit [http://localhost:8000](http://localhost:8000) in your browser.

The application stores all products in `storage/products.json`. You can reset the inventory by deleting the file.

## Project structure

- `app/Core` — minimal routing, view, and redirect helpers
- `app/Http/Controllers` — controllers that orchestrate requests
- `app/Models` — a JSON-backed repository for storing products
- `resources/views` — Pico.css-powered Blade-like templates
- `public/index.php` — the single entry point similar to Laravel's front controller

## Limitations

This is **not** a full Laravel distribution. The goal is to provide a Laravel-inspired developer experience when external package downloads are not possible. The code is deliberately small and easy to adapt to a real Laravel application once you have full framework access.
