<?php
// app/PaymentMethods/PaymentMethodManager.php

namespace App\PaymentMethods;

use App\Helpers\ConfigHelper;

class PaymentMethodManager
{
    protected $methods = [];

    public function __construct()
    {
        $methods = ConfigHelper::getPaymentsMethods();

        foreach ($methods as $method) {
            $method = ucfirst($method);
            $class = "App\\PaymentMethods\\{$method}";
            if (class_exists($class)) {
                $this->methods[] = new $class([]);
            }
        }
    }

    public function getEnabledMethods(): array
    {
        $enabledMethods = [];

        foreach ($this->methods as $method) {
            if ($method->isEnabled()) {
                $enabledMethods[] = $method->getConfiguration();
            }
        }

        return $enabledMethods;
    }
}
