<?php

namespace App\GraphQL\Mutations;

use App\Models\Services\IptvLineUser;
use App\Models\Services\Service;
use App\Services\Iptv\XuiClient;
use App\Settings\Iptv\ProviderIptv;
use Illuminate\Support\Facades\Log;

class IptvMutation
{
    /**
     * Create an IPTV line user on XUI.one and record it locally.
     */
    public function createLineUser($root, array $args): IptvLineUser
    {
        try {
            if (!ProviderIptv::getEnabled()) {
                throw new \Exception('IPTV XUI.one integration is currently disabled in system settings.');
            }

            $service = Service::find($args['service_id']);
            if (!$service) {
                throw new \Exception("Service not found with ID: {$args['service_id']}");
            }

            // Check if service already has a line user
            if ($service->iptvLineUser()->exists()) {
                throw new \Exception('This service already has a linked IPTV Line User.');
            }

            // Parse expire date to timestamp if present
            $expireTimestamp = null;
            if (!empty($args['expire_date'])) {
                $expireTimestamp = is_numeric($args['expire_date']) ? (int) $args['expire_date'] : strtotime($args['expire_date']);
                if ($expireTimestamp === false) {
                    throw new \Exception("Invalid expire_date format provided.");
                }
            }

            $maxConnections = $args['max_connections'] ?? ProviderIptv::getDefaultMaxConnections();
            $bouquets = $args['bouquets'] ?? explode(',', ProviderIptv::getDefaultBouquets());
            $allowedOutputs = $args['allowed_outputs'] ?? ['hls', 'mpegts', 'rtmp'];
            $memberId = ProviderIptv::getDefaultMemberId();

            $apiData = [
                'user' => $args['username'],
                'pass' => $args['password'],
                'max_connections' => $maxConnections,
                'bouquets' => $bouquets,
                'allowed_outputs' => $allowedOutputs,
            ];

            if ($expireTimestamp) {
                $apiData['expire_date'] = $expireTimestamp;
            }
            if ($memberId) {
                $apiData['member_id'] = $memberId;
            }

            $xuiClient = new XuiClient();
            $response = $xuiClient->createLine($apiData);
            $responseJson = $response->json();

            if (!$response->successful() || !$this->isSuccess($responseJson)) {
                $errorMsg = $responseJson['message'] ?? ($responseJson['error'] ?? 'API response indicates failure.');
                Log::error('XUI.one API failed to create line.', ['response' => $responseJson, 'payload' => $apiData]);
                throw new \Exception("XUI.one API Error: " . $errorMsg);
            }

            $dataBlock = $responseJson['data'] ?? [];
            $lineId = $dataBlock['id'] ?? ($dataBlock['line_id'] ?? null);
            
            // XUI.one API might return a altered/generated username/password, use them if present
            $finalUsername = $dataBlock['username'] ?? $args['username'];
            $finalPassword = $dataBlock['password'] ?? $args['password'];

            // Save line user details in local DB
            $lineUser = IptvLineUser::create([
                'service_id' => $args['service_id'],
                'line_id' => $lineId,
                'username' => $finalUsername,
                'password' => $finalPassword,
                'max_connections' => $maxConnections,
                'expire_date' => $expireTimestamp ? date('Y-m-d H:i:s', $expireTimestamp) : null,
                'bouquets' => $bouquets,
                'allowed_outputs' => $allowedOutputs,
                'status' => 'active',
            ]);

            return $lineUser;
        } catch (\Exception $e) {
            Log::error('Error in createIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an IPTV line user on XUI.one and record changes locally.
     */
    public function updateLineUser($root, array $args): IptvLineUser
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            $apiData = [];
            $dbData = [];

            if (isset($args['password'])) {
                $apiData['pass'] = $args['password'];
                $dbData['password'] = $args['password'];
            }

            if (isset($args['max_connections'])) {
                $apiData['max_connections'] = (int) $args['max_connections'];
                $dbData['max_connections'] = (int) $args['max_connections'];
            }

            if (isset($args['expire_date'])) {
                $expireTimestamp = is_numeric($args['expire_date']) ? (int) $args['expire_date'] : strtotime($args['expire_date']);
                $apiData['expire_date'] = $expireTimestamp;
                $dbData['expire_date'] = $expireTimestamp ? date('Y-m-d H:i:s', $expireTimestamp) : null;
            }

            if (isset($args['bouquets'])) {
                $apiData['bouquets'] = $args['bouquets'];
                $dbData['bouquets'] = $args['bouquets'];
            }

            if (isset($args['allowed_outputs'])) {
                $apiData['allowed_outputs'] = $args['allowed_outputs'];
                $dbData['allowed_outputs'] = $args['allowed_outputs'];
            }

            if (!empty($apiData) && !empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->editLine((int)$lineUser->line_id, $apiData);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    $errorMsg = $responseJson['message'] ?? ($responseJson['error'] ?? 'API response indicates failure.');
                    Log::error('XUI.one API failed to edit line.', ['response' => $responseJson, 'line_id' => $lineUser->line_id]);
                    throw new \Exception("XUI.one API Error: " . $errorMsg);
                }

                // Sync changed credentials if XUI.one returns them in the data block
                $dataBlock = $responseJson['data'] ?? [];
                if (isset($dataBlock['username'])) {
                    $dbData['username'] = $dataBlock['username'];
                }
                if (isset($dataBlock['password'])) {
                    $dbData['password'] = $dataBlock['password'];
                }
            }

            $lineUser->update($dbData);

            return $lineUser;
        } catch (\Exception $e) {
            Log::error('Error in updateIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete an IPTV line user from XUI.one and the local DB.
     */
    public function deleteLineUser($root, array $args): array
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (!empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->deleteLine((int) $lineUser->line_id);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    $errorMsg = $responseJson['message'] ?? ($responseJson['error'] ?? 'API response indicates failure.');
                    Log::warning('XUI.one API failed to delete line.', ['response' => $responseJson, 'line_id' => $lineUser->line_id]);
                    throw new \Exception("XUI.one API Error: " . $errorMsg);
                }
            }

            $lineUser->delete();

            return [
                'success' => true,
                'message' => 'IPTV Line User deleted successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in deleteIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Disable IPTV Line
     */
    public function disableLineUser($root, array $args): array
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (!empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->disableLine((int) $lineUser->line_id);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    throw new \Exception("XUI.one API Error: " . ($responseJson['message'] ?? ($responseJson['error'] ?? 'Unknown error')));
                }
            }

            $lineUser->update(['status' => 'disabled']);

            return [
                'success' => true,
                'message' => 'IPTV Line User disabled successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in disableIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Enable IPTV Line
     */
    public function enableLineUser($root, array $args): array
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (!empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->enableLine((int) $lineUser->line_id);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    throw new \Exception("XUI.one API Error: " . ($responseJson['message'] ?? ($responseJson['error'] ?? 'Unknown error')));
                }
            }

            $lineUser->update(['status' => 'active']);

            return [
                'success' => true,
                'message' => 'IPTV Line User enabled successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in enableIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Ban IPTV Line
     */
    public function banLineUser($root, array $args): array
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (!empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->banLine((int) $lineUser->line_id);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    throw new \Exception("XUI.one API Error: " . ($responseJson['message'] ?? ($responseJson['error'] ?? 'Unknown error')));
                }
            }

            $lineUser->update(['status' => 'banned']);

            return [
                'success' => true,
                'message' => 'IPTV Line User banned successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in banIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Unban IPTV Line
     */
    public function unbanLineUser($root, array $args): array
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (!empty($lineUser->line_id)) {
                $xuiClient = new XuiClient();
                $response = $xuiClient->unbanLine((int) $lineUser->line_id);
                $responseJson = $response->json();

                if (!$response->successful() || !$this->isSuccess($responseJson)) {
                    throw new \Exception("XUI.one API Error: " . ($responseJson['message'] ?? ($responseJson['error'] ?? 'Unknown error')));
                }
            }

            $lineUser->update(['status' => 'active']);

            return [
                'success' => true,
                'message' => 'IPTV Line User unbanned successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in unbanIptvLineUser mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Sync IPTV Line User state from XUI.one API to local DB.
     */
    public function syncLineUser($root, array $args): IptvLineUser
    {
        try {
            $lineUser = IptvLineUser::findOrFail($args['id']);

            if (empty($lineUser->line_id)) {
                throw new \Exception("Cannot sync line user without a valid line_id.");
            }

            $xuiClient = new XuiClient();
            $response = $xuiClient->getLine((int) $lineUser->line_id);
            $responseJson = $response->json();

            if (!$response->successful() || !$this->isSuccess($responseJson)) {
                throw new \Exception("XUI.one API Error: " . ($responseJson['message'] ?? ($responseJson['error'] ?? 'Failed to retrieve line details.')));
            }

            // Sync database properties based on details returned from XUI.one
            $apiData = $responseJson['data'] ?? [];
            if (!empty($apiData)) {
                $dbData = [];
                
                if (isset($apiData['username'])) {
                    $dbData['username'] = $apiData['username'];
                }

                if (isset($apiData['password'])) {
                    $dbData['password'] = $apiData['password'];
                }

                if (isset($apiData['max_connections'])) {
                    $dbData['max_connections'] = (int) $apiData['max_connections'];
                }
                
                if (isset($apiData['expire_date'])) {
                    $dbData['expire_date'] = is_numeric($apiData['expire_date']) 
                        ? date('Y-m-d H:i:s', (int) $apiData['expire_date']) 
                        : null;
                }

                // If bouquets list is returned as comma-separated or json string/array
                if (isset($apiData['bouquets'])) {
                    $bouquets = $apiData['bouquets'];
                    if (is_string($bouquets)) {
                        // try to decode JSON, else split by comma
                        $decoded = json_decode($bouquets, true);
                        $dbData['bouquets'] = is_array($decoded) ? $decoded : array_filter(explode(',', $bouquets));
                    } elseif (is_array($bouquets)) {
                        $dbData['bouquets'] = $bouquets;
                    }
                }

                // Sync allowed_outputs
                if (isset($apiData['allowed_outputs'])) {
                    $outputs = $apiData['allowed_outputs'];
                    if (is_string($outputs)) {
                        $decoded = json_decode($outputs, true);
                        $dbData['allowed_outputs'] = is_array($decoded) ? $decoded : array_filter(explode(',', $outputs));
                    } elseif (is_array($outputs)) {
                        $dbData['allowed_outputs'] = $outputs;
                    }
                }

                // Update status locally
                if (isset($apiData['banned']) && (int) $apiData['banned'] === 1) {
                    $dbData['status'] = 'banned';
                } elseif (isset($apiData['active']) && (int) $apiData['active'] === 0) {
                    $dbData['status'] = 'disabled';
                } else {
                    $dbData['status'] = 'active';
                }

                $lineUser->update($dbData);
            }

            return $lineUser;
        } catch (\Exception $e) {
            Log::error('Error in syncIptvLineUserFromApi mutation resolver', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Determine if XUI.one API response indicates a success.
     */
    private function isSuccess(array $responseJson): bool
    {
        $status = $responseJson['status'] ?? false;
        if ($status === true) {
            return true;
        }
        $statusStr = strtolower((string)$status);
        return $statusStr === 'status_success' || $statusStr === 'success' || $statusStr === 'true';
    }
}
