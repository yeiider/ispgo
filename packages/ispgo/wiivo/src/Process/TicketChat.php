<?php

namespace Ispgo\Wiivo\Process;

class TicketChat extends ProcessChat
{
    protected string $_option = "@tik";
    protected string $templateDocument = "Por favor, ingrese su número de documento de identidad sin puntos. En caso de ser NIT, ingréselo sin los dígitos de verificación. 📄🔢";

    public function processMessage($body, $interactions): array
    {
        // TODO: Implement processMessage() method.
        return $this->templateDocument;
    }
}
