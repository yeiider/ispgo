<?php

namespace Ispgo\Wiivo\Process;

class PaymentChat extends ProcessChat
{
    protected string $_option = "@pay";

    public function processMessage($body): string
    {
        // TODO: Implement processMessage() method.
        return "";
    }
}
