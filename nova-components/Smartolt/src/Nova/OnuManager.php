<?php

namespace Ispgo\Smartolt\Nova;

use Laravel\Nova\ResourceTool;

class OnuManager extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'ONU Manager';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'onu-manager';
    }
}
