<?php

namespace App;

use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Exceptions\NovaException;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Resource;

class NovaPermissions extends \Sereny\NovaPermissions\NovaPermissions
{
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws NovaException
     */
    public function menu(Request $request): mixed
    {
        if ($this->menuDisabled) {
            return [];
        }

        $items = [$this->createMenuItem($this->roleResource)];

        if ($this->displayPermissions) {
            $items[] = $this->createMenuItem($this->permissionResource);
        }

        // Agrega el recurso User al menú de permisos
        $items[] = $this->createMenuItem(User::class);

        return MenuSection::make(__('Roles & Permissions'), $items)
            ->icon('shield-check')
            ->collapsable();
    }

    /**
     * @param  class-string<Resource>  $resourceClass
     * @return MenuItem
     */
    protected function createMenuItem($resourceClass): MenuItem
    {
        return MenuItem::make($resourceClass::label())
            ->path('/resources/'.$resourceClass::uriKey());
    }
}
