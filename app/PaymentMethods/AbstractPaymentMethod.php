<?php

// app/PaymentMethods/AbstractPaymentMethod.php

namespace App\PaymentMethods;

use App\Helpers\ConfigHelper;

abstract class AbstractPaymentMethod implements PaymentMethodInterface
{
    protected $config;
    protected $currency;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return ConfigHelper::getConfigValue($this->getPath() . $this->getFiledEnabled()) == 1;
    }

    abstract public function getConfiguration(): array;

    abstract protected function getPath(): string;

    abstract protected function getFiledEnabled(): string;
}
