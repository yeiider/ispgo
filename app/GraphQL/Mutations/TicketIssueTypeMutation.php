<?php

namespace App\GraphQL\Mutations;

use App\Models\TicketIssueType;

class TicketIssueTypeMutation
{
    public function delete($root, array $args): array
    {
        $issueType = TicketIssueType::findOrFail($args['id']);
        $issueType->delete();

        return [
            'success' => true,
            'message' => 'Tipo de problema eliminado exitosamente.',
        ];
    }
}
