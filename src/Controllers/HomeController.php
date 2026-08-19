<?php

namespace App\Controllers;

use Carbon\Carbon;

class HomeController
{
    public function index()
    {
        $env = getenv('APP_ENV') ?: 'Unknown';
        $time = Carbon::now()->format('l, F j, Y g:i A');

        echo "<h1>Hello from PHP Multi-Environment Demo!</h1>";
        echo "<p>Environment: <strong>$env</strong></p>";
        echo "<p>Server time: <strong>$time</strong></p>";
        echo "<p>Testing Prod pipeline with branch-based trigger</p>";
    }

    public function about()
    {
        echo "<h1>About This App</h1>";
        echo "<p>This is a simple PHP app deployed via a multi-environment CI/CD pipeline.</p>";
    }
}
