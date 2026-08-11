<?php

namespace App\GraphQL\Queries;

use App\Services\Iptv\XuiClient;
use Illuminate\Support\Facades\Log;

class IptvQuery
{
    /**
     * Get bouquets from XUI.one API
     */
    public function getBouquets($root, array $args): array
    {
        try {
            $xuiClient = new XuiClient();
            $response = $xuiClient->getBouquets();
            
            if (!$response->successful()) {
                Log::error('XUI.one getBouquets API error', ['body' => $response->body()]);
                throw new \Exception('Failed to retrieve bouquets from XUI.one API.');
            }

            $data = $response->json();
            $result = [];

            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    if (is_array($val)) {
                        $result[] = [
                            'id' => (string) ($val['id'] ?? $val['bouquet_id'] ?? $key),
                            'name' => (string) ($val['name'] ?? $val['bouquet_name'] ?? 'Bouquet ' . $key),
                        ];
                    } else {
                        // In case XUI returns key-value pair of id => name
                        $result[] = [
                            'id' => (string) $key,
                            'name' => (string) $val,
                        ];
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error in iptvBouquets query resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get packages from XUI.one API
     */
    public function getPackages($root, array $args): array
    {
        try {
            $xuiClient = new XuiClient();
            $response = $xuiClient->getPackages();

            if (!$response->successful()) {
                Log::error('XUI.one getPackages API error', ['body' => $response->body()]);
                throw new \Exception('Failed to retrieve packages from XUI.one API.');
            }

            $data = $response->json();
            $result = [];

            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    if (is_array($val)) {
                        $result[] = [
                            'id' => (string) ($val['id'] ?? $val['package_id'] ?? $key),
                            'name' => (string) ($val['name'] ?? $val['package_name'] ?? 'Package ' . $key),
                        ];
                    } else {
                        $result[] = [
                            'id' => (string) $key,
                            'name' => (string) $val,
                        ];
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error in iptvPackages query resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get raw lines list from XUI.one API
     */
    public function getLinesFromApi($root, array $args)
    {
        try {
            $xuiClient = new XuiClient();
            $response = $xuiClient->getLines();

            if (!$response->successful()) {
                Log::error('XUI.one getLines API error', ['body' => $response->body()]);
                throw new \Exception('Failed to retrieve lines from XUI.one API.');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error in iptvLinesFromApi query resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
