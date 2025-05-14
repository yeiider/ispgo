<?php
namespace Ispgo\Siigo;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
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
        return $this->http->request($method, $uri, $opts);
    }

    public function createCustomer(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/customers', ['json' => $payload]);
    }

    public function createInvoice(array $payload): ResponseInterface
    {
        return $this->req('POST', '/v1/invoices', ['json' => $payload]);
    }

    public function stampInvoice(string $id): ResponseInterface
    {
        return $this->req('POST', "/v1/invoices/{$id}/stamp");
    }
}
