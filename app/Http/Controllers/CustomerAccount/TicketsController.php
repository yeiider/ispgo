<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Settings\Config\Sources\IssueTypes;
use App\Settings\SupportProviderConfig;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Nova\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;

class TicketsController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Customer/Tickets/Index', [
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
            'ticketCreateUrl' => route('tickets.create'),
        ]);
    }

    private function getCustomerId(): int|string|null
    {
        return Auth::guard('customer')->id();
    }

    public function create(): \Inertia\Response
    {
        if (!SupportProviderConfig::allowCustomerCreateTickets()) {
            abort(403);
        }

        $issueTypes = IssueTypes::getConfig();

        return Inertia::render('Customer/Tickets/Create', compact('issueTypes'));
    }


}
