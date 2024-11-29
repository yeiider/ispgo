<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Customers\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\CountriesService;

class AddressBook extends Controller
{

    public function __construct(protected CountriesService $countriesService)
    {

    }

    public function index(): \Inertia\Response
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
                    'address_type' => $address->address_type,
                    'created_at' => $address->created_at,
                    'updated_at' => $address->updated_at,
                ]),
            'addressCreateUrl' => route('address.create'),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Customer/AddressBook/Create', [
            'countries' => $this->countriesService->countriesOptions()
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'address' => 'required',
            'city' => 'required',
            'state_province' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        $address = Address::create([
            'customer_id' => Auth::guard('customer')->id(),
            ...$request->all(),
        ]);

        $address->save();
        return redirect()
            ->route('addresses')
            ->with('status', __('Address created successfully.'));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'address' => 'required',
            'city' => 'required',
            'state_province' => 'required',
        ]);

        $address = Address::query()->findOrFail($id);
        $address->update($request->all());
        $address->save();
        return redirect()
            ->route('addresses')
            ->with('status', __('Address updated successfully.'));

    }

    public function edit(int $id): \Inertia\Response
    {
        if (!$id) {
            abort(404);
        }

        $address = Address::query()->findOrFail($id);
        return Inertia::render('Customer/AddressBook/Edit', [
            'address' => $address,
            'id' => $address->id,
            'countries' => $this->countriesService->countriesOptions()
        ]);
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        if (!$id) {
            abort(404);
        }
        $address = Address::query()->findOrFail($id);
        $address->delete();
        return redirect()
            ->route('addresses')
            ->with('status', __('Address deleted successfully.'));
    }
}
