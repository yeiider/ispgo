<?php

namespace App\Services;

use App\Models\HtmlTemplate;

class HtmlTemplateEngine extends BaseTemplateEngine
{

    /**
     * Busca subplantillas en la tabla html_templates
     */
    protected function findTemplateById($id)
    {
        return HtmlTemplate::find($id);
    }
}
