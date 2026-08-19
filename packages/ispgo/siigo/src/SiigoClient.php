<?php
namespace Ispgo\Siigo;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class SiigoClient
{
    private Client $http;
    private array $cfg;

    public function __construct(array $cfg)
    {
        $this->cfg  = $cfg;
        $this->http = new Client(['base_uri' => $cfg['base_url']]);
    }

    private function token(): string
    {
        return Cache::remember('siigo.token', 55*60, function () {
            $res = $this->http->post('/auth', [
                'json' => [
                    'username'   => $this->cfg['username'],
                    'access_key' => $this->cfg['access_key'],
                ],
                'headers' => ['Accept' => 'application/json'],
            ]);
            return json_decode((string) $res->getBody(), true)['access_token'];
        });
    }

    private function req(string $method, string $uri, array $opts = []): ResponseInterface
    {
        $opts['headers']['Authorization'] = 'Bearer '.$this->token();
        if (!empty($this->cfg['partner_id'])) {
            $opts['headers']['Partner-Id'] = $this->cfg['partner_id'];
        }

        $attempts = 0;
        $maxAttempts = 5;

        while (true) {
            try {
                $attempts++;
                return $this->http->request($method, $uri, $opts);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->getResponse() && $e->getResponse()->getStatusCode() === 429 && $attempts < $maxAttempts) {
                    $sleepSeconds = 5; // Default fallback
                    try {
                        // Rewind response body just in case
                        $e->getResponse()->getBody()->seek(0);
                        $body = json_decode((string) $e->getResponse()->getBody(), true);
                        $msg = $body['Errors'][0]['Message'] ?? '';
                        if (preg_match('/Try again in (\d+) seconds/i', $msg, $matches)) {
                            $sleepSeconds = (int) $matches[1] + 1;
                        }
                    } catch (\Exception $ex) {}

                    Log::warning("Siigo API Rate Limited (429) on request to {$uri}. Sleeping for {$sleepSeconds} seconds before attempt " . ($attempts + 1) . "...");
                    sleep($sleepSeconds);
                    continue;
                }
                throw $e;
            }
        }
    }

    public function getCustomer(string $identification): ResponseInterface
    {
        return $this->req('GET', '/v1/customers', ['query' => ['identification' => $identification]]);
    }

    public function getCustomerByUuid(string $id): ResponseInterface
    {
        return $this->req('GET', "/v1/customers/{$id}");
    }

    public function updateCustomer(string $id, array $payload): ResponseInterface
    {
        return $this->req('PUT', "/v1/customers/{$id}", ['json' => $payload]);
    }

    public function createCustomer(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/customers', ['json' => $payload]);
    }

    public function createInvoice(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/invoices', ['json' => $payload]);
    }

    public function getInvoiceByUuid(string $id): ResponseInterface
    {
        return $this->req('GET', "/v1/invoices/{$id}");
    }

    public function stampInvoice(string $id): ResponseInterface
    {
        return $this->req('POST', "/v1/invoices/{$id}/stamp");
    }

    public function createVoucher(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/vouchers', ['json' => $payload]);
    }

    public function createCreditNote(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/credit-notes', ['json' => $payload]);
    }

    public function stampCreditNote(string $id): ResponseInterface
    {
        return $this->req('POST', "/v1/credit-notes/{$id}/stamp");
    }

    public function getCostCenters(): ResponseInterface
    {
        return $this->req('GET', '/v1/cost-centers');
    }

    public function getDocumentTypes(?string $type = null): ResponseInterface
    {
        $query = $type ? ['type' => $type] : [];
        return $this->req('GET', '/v1/document-types', ['query' => $query]);
    }
}
