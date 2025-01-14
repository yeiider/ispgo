<?php

namespace App\Services;

use App\Models\EmailTemplate;

class EmailTemplateEngine extends BaseTemplateEngine
{

    /**
     * Busca subplantillas en la tabla email_templates
     */
    protected function findTemplateById($id)
    {
        return EmailTemplate::find($id);
    }
}
