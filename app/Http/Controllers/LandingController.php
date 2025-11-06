<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    public function show(): void
    {
        $this->view('landing', [
            'splashPage' => true,
            'pageTitle' => 'Welcome',
        ]);
    }
}
