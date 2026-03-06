<?php

namespace App\GraphQL\Mutations;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketMutation
{
    /**
     * Create a new ticket
     */
    public function create($_, array $args)
    {
        $userIds = $args['user_ids'] ?? [];
        unset($args['user_ids']);

        $ticket = Ticket::create([
            'customer_id' => $args['customer_id'] ?? null,
            'service_id' => $args['service_id'] ?? null,
            'issue_type' => $args['issue_type'],
            'priority' => $args['priority'],
            'status' => $args['status'] ?? 'open',
            'title' => $args['title'],
            'description' => $args['description'],
            'contact_method' => $args['contact_method'] ?? null,
        ]);

        if (!empty($userIds)) {
            $ticket->assignUsers($userIds);
        }

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Update an existing ticket
     */
    public function update($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['id']);

        $updateData = [];
        if (isset($args['customer_id'])) $updateData['customer_id'] = $args['customer_id'];
        if (isset($args['service_id'])) $updateData['service_id'] = $args['service_id'];
        if (isset($args['issue_type'])) $updateData['issue_type'] = $args['issue_type'];
        if (isset($args['priority'])) $updateData['priority'] = $args['priority'];
        if (isset($args['status'])) $updateData['status'] = $args['status'];
        if (isset($args['title'])) $updateData['title'] = $args['title'];
        if (isset($args['description'])) $updateData['description'] = $args['description'];
        if (isset($args['contact_method'])) $updateData['contact_method'] = $args['contact_method'];
        if (isset($args['resolution_notes'])) $updateData['resolution_notes'] = $args['resolution_notes'];

        $ticket->update($updateData);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Delete a ticket
     */
    public function delete($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['id']);
        $ticket->delete();

        return [
            'success' => true,
            'message' => 'Ticket deleted successfully'
        ];
    }

    /**
     * Assign users to a ticket
     */
    public function assignUsers($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->assignUsers($args['user_ids']);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Remove a user from a ticket
     */
    public function removeUser($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->removeUser($args['user_id']);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Assign customer to a ticket
     */
    public function assignCustomer($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->update(['customer_id' => $args['customer_id'] ?? null]);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }

    /**
     * Assign service to a ticket
     */
    public function assignService($_, array $args)
    {
        $ticket = Ticket::findOrFail($args['ticket_id']);
        $ticket->update(['service_id' => $args['service_id'] ?? null]);

        return $ticket->fresh(['users', 'customer', 'service', 'comments', 'attachments']);
    }
}
