<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DefaultsController extends Controller
{
    public function updateDefaults(Request $request)
    {
        // Validate the input data if needed
        $request->validate([
            'default_discount' => 'required|numeric',
            'free_delivery_threshold' => 'required|numeric',
        ]);

        // Read the .env file
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Update the default discount
        $defaultDiscount = $request->input('default_discount');
        $envContent = preg_replace(
            '/^DEFAULTS_DISCOUNT=.*$/m',
            'DEFAULTS_DISCOUNT=' . $defaultDiscount,
            $envContent
        );

        // Update the free delivery threshold
        $freeDeliveryThreshold = $request->input('free_delivery_threshold');
        $envContent = preg_replace(
            '/^DEFAULTS_DELIVERY=.*$/m',
            'DEFAULTS_DELIVERY=' . $freeDeliveryThreshold,
            $envContent
        );

        // Save the updated .env file
        File::put($envPath, $envContent);

        // Optionally, you can add a flash message or redirect the user
        return redirect()->back()->with('success', 'Default values updated successfully.');
    }
}
