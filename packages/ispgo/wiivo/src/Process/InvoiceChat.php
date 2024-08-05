<?php

namespace Ispgo\Wiivo\Process;

use App\Models\Customers\Customer;
use App\PaymentMethods\Wompi;

class InvoiceChat extends ProcessChat
{
    protected string $_option = "@inv";
    protected array $templateDocument = ["message" => "Por favor, ingrese su número de documento de identidad sin puntos. En caso de ser NIT, ingréselo sin los dígitos de verificación. 📄🔢"];

    public function processMessage($body, $interactions): array
    {
        return $this->getResponseTemplate($interactions);
    }

    /**
     * @return string
     */
    public function getResponseTemplate($interactions): array
    {
        return match (count($interactions)) {
            1 => $this->templateDocument,
            2 => $this->getTemplateInvoice($interactions),
            3 => $this->getLinkPago($interactions),
            default => "❌ Esta opcion no esta disponible intente de nuevo. 🙏"
        };
    }

    private function getTemplateInvoice($interactions): array
    {
        $message = $interactions[count($interactions) - 1];
        $dni = $message['message'];
        $customer = Customer::findByIdentityDocument(trim($dni));

        if (!$customer) {
            return ["message" => "Lo siento, no encontramos ningún cliente asociado con ese número de documento. Por favor, verifica e intenta nuevamente."];
        }

        $invoice = $customer->getLastInvoice();

        if (!$invoice) {
            return ["message" => "Lo siento, no encontramos ninguna factura asociada con ese número de documento. Por favor, verifica e intenta nuevamente."];
        }

        $saldoPendiente = number_format($invoice->outstanding_balance, 2, ',', '.');
        return ["message" => "Estimado/a *{$customer->full_name}*, su saldo pendiente es de *\$ {$saldoPendiente}*. Gracias por su atención.",
            'buttons' => [
                ['id' => '3', 'text' => 'Realizar pago'],
                ['id' => '4', 'text' => 'Salir']
            ]];
    }

    private function getLinkPago($interactions): array
    {
        $message = $this->getOptions($interactions, 2);
        $message = reset($message);
        $dni = $message['message'];
        $customer = Customer::findByIdentityDocument(trim($dni));
        $invoice = $customer->getLastInvoice();
        $link = Wompi::generatedLinkPayment($invoice);
        if (isset($link['data']['id'])) {
            $expires_at = $link['data']['expires_at'];
            $link_pago = "https://checkout.wompi.co/l/" . $link['data']['id'];

            return ["message" => "Estimado cliente, tu link de pago es $link_pago y expira el $expires_at. Gracias por tu atención. 💳🕒"];
        }
        return ["message" => "❌ Error al generar el link de pago. Por favor, intente de nuevo más tarde. 🙏"];
    }
}
