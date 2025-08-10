<?php

require_once 'vendor/autoload.php';

use App\Models\Inventory\EquipmentAssignment;
use App\Models\User;
use Illuminate\Foundation\Application;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Equipment Assignments API\n";
echo "=================================\n\n";

// Test 1: Check if EquipmentAssignment model exists and can be queried
echo "1. Testing EquipmentAssignment model:\n";
try {
    $assignments = EquipmentAssignment::all();
    echo "   ✓ EquipmentAssignment model accessible\n";
    echo "   ✓ Total equipment assignments in database: " . $assignments->count() . "\n";

    if ($assignments->count() > 0) {
        $firstAssignment = $assignments->first();
        echo "   ✓ First assignment ID: " . $firstAssignment->id . "\n";
        echo "   ✓ Assigned to user ID: " . $firstAssignment->user_id . "\n";
        echo "   ✓ Product ID: " . $firstAssignment->product_id . "\n";
        echo "   ✓ Status: " . $firstAssignment->status . "\n";

        // Test relationships
        if ($firstAssignment->user) {
            echo "   ✓ User relationship works: " . $firstAssignment->user->name . "\n";
        } else {
            echo "   ! No user associated with first assignment\n";
        }

        if ($firstAssignment->product) {
            echo "   ✓ Product relationship works: " . $firstAssignment->product->name . "\n";
        } else {
            echo "   ! No product associated with first assignment\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Test query for specific user
echo "\n2. Testing user-specific equipment assignments:\n";
try {
    $user = User::first();
    if ($user) {
        echo "   ✓ Testing with user: " . $user->name . " (ID: " . $user->id . ")\n";

        $userAssignments = EquipmentAssignment::where('user_id', $user->id)
            ->with('product')
            ->get();

        echo "   ✓ Equipment assignments for user: " . $userAssignments->count() . "\n";

        if ($userAssignments->count() > 0) {
            foreach ($userAssignments as $assignment) {
                echo "     - Assignment ID: " . $assignment->id .
                     ", Product: " . ($assignment->product ? $assignment->product->name : 'N/A') .
                     ", Status: " . $assignment->status . "\n";
            }
        }
    } else {
        echo "   ! No users found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Test controller method simulation
echo "\n3. Testing controller method logic:\n";
try {
    $user = User::first();
    if ($user) {
        // Simulate authentication
        auth()->login($user);
        echo "   ✓ User authenticated: " . $user->name . "\n";

        // Simulate the controller method logic
        $equipmentAssignments = EquipmentAssignment::where('user_id', auth()->id())
            ->with('product')
            ->get();

        echo "   ✓ Equipment assignments query successful\n";
        echo "   ✓ Results count: " . $equipmentAssignments->count() . "\n";

        // Test JSON structure
        $response = [
            'success' => true,
            'data' => [
                'equipment_assignments' => $equipmentAssignments
            ]
        ];

        echo "   ✓ Response structure created successfully\n";
        echo "   ✓ Response success: " . ($response['success'] ? 'true' : 'false') . "\n";

        auth()->logout();
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 4: Check if controller class can be instantiated
echo "\n4. Testing MobileAppController with new method:\n";
try {
    $controller = new App\Http\Controllers\API\AppMovil\MobileAppController();
    echo "   ✓ MobileAppController can be instantiated\n";

    // Check if method exists
    if (method_exists($controller, 'getEquipmentAssignments')) {
        echo "   ✓ getEquipmentAssignments method exists\n";
    } else {
        echo "   ✗ getEquipmentAssignments method not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 5: Check routes are registered
echo "\n5. Testing route registration:\n";
try {
    $routes = app('router')->getRoutes();
    $equipmentRoute = null;

    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'app-movil/equipment-assignments') !== false) {
            $equipmentRoute = $uri;
            break;
        }
    }

    if ($equipmentRoute) {
        echo "   ✓ Equipment assignments route found: " . $equipmentRoute . "\n";
    } else {
        echo "   ! Equipment assignments route not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
