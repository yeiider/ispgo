<?php

namespace Ispgo\Mikrotik\Helper;

use Exception;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;

class HelperMikrotikData
{
    public static function getConfig(): array
    {
        $enabled = MikrotikConfigProvider::getEnabled();

        if (!$enabled) {
            throw new Exception('MikroTik connection is disabled in the settings.');
        }

        $host = MikrotikConfigProvider::getHost();
        $sshPort = MikrotikConfigProvider::getPort() ?? 22; // Puerto por defecto SSH
        $username = MikrotikConfigProvider::getUsername();
        $password = MikrotikConfigProvider::getPassword();
        $timeout = MikrotikConfigProvider::getTimeout() ?? 60;

        return [
            'host' => $host,
            'user' => $username,
            'pass' => $password,
            'ssh_port' => (int)$sshPort,
            'ssh_timeout' => (int)$timeout
        ];

    }
}
