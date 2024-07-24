<?php

namespace App\PaymentMethods;

interface PaymentMethodInterface
{
    public function getConfiguration(): array;
    public function isEnabled(): bool;
}
