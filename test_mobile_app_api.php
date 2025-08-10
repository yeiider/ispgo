<?php

require_once 'vendor/autoload.php';

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Mobile App API Endpoints\n";
echo "================================\n\n";

// Test 1: Check if Ticket model and forAuthenticatedUser method exists
echo "1. Testing Ticket::forAuthenticatedUser() method:\n";
try {
    // Test without authentication (should return empty collection)
    $tickets = Ticket::forAuthenticatedUser();
    echo "   ✓ Method exists and returns: " . get_class($tickets) . "\n";
    echo "   ✓ Count of tickets (no auth): " . $tickets->count() . "\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Check if we can get a user and simulate authentication
echo "\n2. Testing with simulated authentication:\n";
try {
    $user = User::first();
    if ($user) {
        // Simulate authentication
        auth()->login($user);
        echo "   ✓ User authenticated: " . $user->name . " (ID: " . $user->id . ")\n";

        $tickets = Ticket::forAuthenticatedUser();
        echo "   ✓ Tickets for authenticated user: " . $tickets->count() . "\n";

        if ($tickets->count() > 0) {
            $firstTicket = $tickets->first();
            echo "   ✓ First ticket ID: " . $firstTicket->id . "\n";

            // Test relationships
            if ($firstTicket->service) {
                echo "   ✓ Service relationship works: " . $firstTicket->service->id . "\n";
            } else {
                echo "   ! No service associated with first ticket\n";
            }

            if ($firstTicket->customer) {
                echo "   ✓ Customer relationship works: " . $firstTicket->customer->id . "\n";
            } else {
                echo "   ! No customer associated with first ticket\n";
            }
        }

        auth()->logout();
    } else {
        echo "   ! No users found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Check if controller class can be instantiated
echo "\n3. Testing MobileAppController instantiation:\n";
try {
    $controller = new App\Http\Controllers\API\AppMovil\MobileAppController();
    echo "   ✓ MobileAppController can be instantiated\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 4: Check routes are registered
echo "\n4. Testing route registration:\n";
try {
    $routes = app('router')->getRoutes();
    $mobileRoutes = [];

    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'app-movil') !== false) {
            $mobileRoutes[] = $uri;
        }
    }

    if (count($mobileRoutes) > 0) {
        echo "   ✓ Mobile app routes found:\n";
        foreach ($mobileRoutes as $route) {
            echo "     - " . $route . "\n";
        }
    } else {
        echo "   ! No mobile app routes found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
