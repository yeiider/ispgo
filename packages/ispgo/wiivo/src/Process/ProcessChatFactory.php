<?php

namespace Ispgo\Wiivo\Process;

class ProcessChatFactory
{
    /**
     * @throws \Exception
     */
    public static function getProcessor($option): TicketChat|PaymentChat|InvoiceChat
    {
        switch ($option) {
            case '1':
                return new InvoiceChat();
            case '2':
                return new PaymentChat();
            case '3':
                return new TicketChat();
            default:
                throw new \Exception("Opción no válida");
        }
    }
}
