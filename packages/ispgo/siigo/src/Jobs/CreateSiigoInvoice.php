<?php
namespace Ispgo\Siigo\Jobs;

use Ispgo\Siigo\SiigoClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSiigoInvoice implements ShouldQueue
{
    use Queueable;

    public function __construct(private array $invoice) {}

    public function handle(SiigoClient $siigo)
    {
        $payload = $this->invoice;
        $response = $siigo->createInvoice($payload);
        $id = json_decode((string) $response->getBody(), true)['id'] ?? null;
        if ($id) {
            $siigo->stampInvoice($id);
        }
    }
}
