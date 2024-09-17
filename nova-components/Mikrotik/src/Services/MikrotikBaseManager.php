<?php

namespace Ispgo\Mikrotik\Services;

use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Helper\HelperMikrotikData;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Ispgo\Mikrotik\MikrotikApi;

abstract class MikrotikBaseManager
{
    protected $mikrotikApi;

    /**
     * @throws \Exception
     */
    protected function init(): void
    {
        $config = HelperMikrotikData::getConfig();
        Log::info("Mikrotik configuration is loaded INIT");
        $this->mikrotikApi = new MikrotikApi($config);

    }
}
