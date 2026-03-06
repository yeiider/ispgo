<?php

namespace App\Settings\Config\Sources;

use App\Models\User;
use Ispgo\SettingsManager\Source\ConfigProviderInterface;

class Users implements ConfigProviderInterface
{

    static public function getConfig(): array
    {
        $users = User::all();
        //$users = User::createInvoiceUsers();
        $options = [];

        foreach ($users as $user) {
            $options[] = ["label" => $user->name, "value" => $user->id];
        }
        return $options;
    }
}
