<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Ticket;
use App\Settings\Config\Sources\IssueTypes;
use App\Settings\SupportProviderConfig;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\{Request, RedirectResponse};

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
            'allowCustomerCreateTickets' => SupportProviderConfig::allowCustomerCreateTickets()
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
        $services = Service::getServicesByCustomerID($this->getCustomerId());

        return Inertia::render('Customer/Tickets/Create', compact('issueTypes', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!SupportProviderConfig::allowCustomerCreateTickets()) {
            abort(403);
        }

        $request->validate([
            'service_id' => 'required',
            'issue_type' => 'required',
            'title' => 'required',
            'description' => 'required',
            'contact_method' => 'required',
        ], [
            'service_id.required' => __('Please select a service.'),
            'issue_type.required' => __('Please select an issue type.'),
            'title.required' => __('Please enter a title.'),
            'description.required' => __('Please enter a description.'),
            'contact_method.required' => __('Please select a contact method.'),
        ]);

        $attachments = null;

        if ($request->hasFile('attachments')) {
            $request->validate([
                'attachments' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            ], [
                'attachments.mimes' => __('Only images, pdf, doc, and docx are allowed.'),
            ]);

            $file = $request->file('attachments');
            $path = $file->store('/', 'public');

            $attachments = $path;
        }

        Ticket::create([
            'customer_id' => $this->getCustomerId(),
            'service_id' => $request->input('service_id'),
            'issue_type' => $request->input('issue_type'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'contact_method' => $request->input('contact_method'),
            'attachments' => $attachments,
            'priority' => SupportProviderConfig::defaultTicketProperty() ?? 'low',
            'status' => SupportProviderConfig::defaultTicketStatus() ?? 'open',
        ]);

        return redirect()->route('tickets')->with('status', [
            'message' => 'Ticket created successfully.',
            'status_code' => 201,
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        if (!$id) abort(404);
        $ticket = Ticket::query()->findOrFail($id);
        dd($ticket);
    }

}
