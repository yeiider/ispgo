<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Nova\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;

class TicketsController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Tickets', [
            'tickets' => Ticket::where('customer_id', $this->getCustomerId())
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
                ->through(fn($ticket) => [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'description' => $ticket->description,
                    'resolution_notes' => $ticket->resolution_notes,
                    'contact_method' => $ticket->contact_method,
                    'closed_at' => $ticket->closed_at,
                ]),
        ]);
    }

    private function getCustomerId(): int|string|null
    {
        return Auth::guard('customer')->id();
    }
}
