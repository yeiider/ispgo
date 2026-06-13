<?php

namespace App\Services\Iptv;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Settings\Iptv\ProviderIptv;

class XuiClient
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $domain = rtrim(ProviderIptv::getUrl() ?? '', '/');
        $accessCode = trim(ProviderIptv::getAccessCode() ?? '', '/');
        
        // Base URL is http://[DOMINIO_XUI_O_IP]:[PUERTO]/[ACCESS_CODE]/
        if ($domain && $accessCode) {
            $this->baseUrl = $domain . '/' . $accessCode . '/';
        } else {
            $this->baseUrl = '';
        }

        $this->apiKey = ProviderIptv::getApiKey() ?? '';
    }

    /**
     * Send HTTP request to XUI.one API
     */
    private function request(string $action, array $params = [], string $method = 'post')
    {
        if (empty($this->baseUrl) || empty($this->apiKey)) {
            Log::error('XUI.one API Client is not configured properly.', [
                'base_url' => $this->baseUrl,
                'has_api_key' => !empty($this->apiKey)
            ]);
            throw new \Exception('XUI.one API is not properly configured. Please check your IPTV settings.');
        }

        $queryParams = [
            'api_key' => $this->apiKey,
            'action' => $action
        ];

        $url = $this->baseUrl;
        
        $request = Http::asForm();

        if (strtolower($method) === 'get') {
            $allParams = array_merge($queryParams, $params);
            Log::info("XUI.one API GET request to: {$url}", ['params' => $allParams]);
            return $request->get($url, $allParams);
        }

        // For POST requests, we append query params to the URL, and send variables in the POST body.
        $urlWithQuery = $url . '?' . http_build_query($queryParams);
        Log::info("XUI.one API POST request to: {$urlWithQuery}", ['body' => $params]);
        return $request->post($urlWithQuery, $params);
    }

    /**
     * Create a new IPTV User Line
     */
    public function createLine(array $data)
    {
        // Normalize bouquets: always send as comma-separated string
        if (isset($data['bouquets'])) {
            $bouquetArray = is_array($data['bouquets']) ? $data['bouquets'] : explode(',', $data['bouquets']);
            $data['bouquets'] = implode(',', $bouquetArray);
        }

        // Normalize allowed_outputs: convert array to comma-separated string
        if (isset($data['allowed_outputs'])) {
            $data['allowed_outputs'] = is_array($data['allowed_outputs'])
                ? implode(',', $data['allowed_outputs'])
                : $data['allowed_outputs'];
        } else {
            $data['allowed_outputs'] = 'hls,mpegts,rtmp';
        }

        return $this->request('create_line', $data, 'post');
    }

    /**
     * Retrieve all lines
     */
    public function getLines(array $params = [])
    {
        return $this->request('get_lines', $params, 'get');
    }

    /**
     * Retrieve details of a specific line
     */
    public function getLine(int $lineId)
    {
        return $this->request('get_line', ['line_id' => $lineId], 'get');
    }

    /**
     * Edit/Update an IPTV Line
     */
    public function editLine(int $lineId, array $data)
    {
        if (isset($data['bouquets'])) {
            $bouquetArray = is_array($data['bouquets']) ? $data['bouquets'] : explode(',', $data['bouquets']);
            $data['bouquets'] = implode(',', $bouquetArray);
        }

        if (isset($data['allowed_outputs'])) {
            $data['allowed_outputs'] = is_array($data['allowed_outputs'])
                ? implode(',', $data['allowed_outputs'])
                : $data['allowed_outputs'];
        } else {
            $data['allowed_outputs'] = 'hls,mpegts,rtmp';
        }

        $payload = array_merge(['line_id' => $lineId], $data);
        return $this->request('edit_line', $payload, 'post');
    }

    /**
     * Delete an IPTV Line
     */
    public function deleteLine(int $lineId)
    {
        return $this->request('delete_line', ['line_id' => $lineId], 'post');
    }

    /**
     * Disable IPTV Line (Suspend stream)
     */
    public function disableLine(int $lineId)
    {
        return $this->request('disable_line', ['line_id' => $lineId], 'post');
    }

    /**
     * Enable IPTV Line
     */
    public function enableLine(int $lineId)
    {
        return $this->request('enable_line', ['line_id' => $lineId], 'post');
    }

    /**
     * Ban IPTV Line
     */
    public function banLine(int $lineId)
    {
        return $this->request('ban_line', ['line_id' => $lineId], 'post');
    }

    /**
     * Unban IPTV Line
     */
    public function unbanLine(int $lineId)
    {
        return $this->request('unban_line', ['line_id' => $lineId], 'post');
    }

    /**
     * Get list of Bouquets (Packages)
     */
    public function getBouquets()
    {
        return $this->request('get_bouquets', [], 'get');
    }

    /**
     * Get list of Packages
     */
    public function getPackages()
    {
        return $this->request('get_packages', [], 'get');
    }
}
