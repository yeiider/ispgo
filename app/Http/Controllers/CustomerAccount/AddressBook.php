<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Customers\Address;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AddressBook extends Controller
{

    public function index()
    {
        $customer = Auth::guard('customer');
        return Inertia::render('Customer/AddressBook/Index', [
            'address_book' => Address::where('customer_id', $customer->id())
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
            ->through(fn($address) => [
                'id' => $address->id,
                'address' => $address->address,
                'city' => $address->city,
                'state_province' => $address->state_province,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
                'created_at' => $address->created_at,
                'updated_at' => $address->updated_at,
            ])
        ]);
    }


}
