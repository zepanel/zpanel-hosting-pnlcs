<?php

namespace Modules\Servers\ZPanelHosting;

/**
 * zPanel Web Hosting & Reseller Module for PNLCS (Laravel 13 Architecture)
 * Reference: https://github.com/Panelica/pnlcs
 * 
 * Resmi Port Mimarisi:
 * - 8026 / TCP (HTTPS): Müşteri Paneli
 * - 8036 / TCP (HTTPS): Root / Yönetici Paneli
 * - 8046 / TCP (HTTPS): Webmail
 * - 8056 / TCP (HTTPS): Core REST API
 */
class ZPanelHostingServer
{
    protected array $server;
    protected array $params;

    public function __construct(array $server = [], array $params = [])
    {
        $this->server = $server;
        $this->params = $params;
    }

    protected function callApi(string $method, string $path, array $data = []): array
    {
        $host = trim($this->server['ip'] ?? $this->server['hostname'] ?? $this->params['domain'] ?? '', '/');
        $apiKey = trim($this->server['api_key'] ?? $this->server['password'] ?? '');
        $port = 8056;

        if (empty($host) || empty($apiKey)) {
            return ['status' => false, 'message' => 'Sunucu IP/Host veya API Token tanımlanmamış.'];
        }

        $cleanUri = ltrim($path, '/');
        if (!str_starts_with($cleanUri, 'v1/')) {
            $cleanUri = 'v1/' . $cleanUri;
        }

        $url = "https://{$host}:{$port}/{$cleanUri}";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: PNLCS-zPanel-Hosting/1.0',
        ]);

        $method = strtoupper($method);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'PATCH' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $res = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($res === false) {
            return ['status' => false, 'message' => 'Bağlantı hatası: ' . $err, 'data' => null];
        }

        $json = json_decode((string)$res, true);
        $isOk = ($code >= 200 && $code < 300);

        return [
            'status' => $isOk,
            'http_code' => $code,
            'data' => $json,
            'message' => $json['message'] ?? ($isOk ? 'Başarılı' : "HTTP Hata {$code}"),
        ];
    }

    protected function findCustomerId(string $username): ?string
    {
        $res = $this->callApi('GET', "customers?q=" . urlencode($username));
        if ($res['status'] && !empty($res['data']['items'])) {
            foreach ($res['data']['items'] as $item) {
                if (strcasecmp($item['username'], $username) === 0) {
                    return $item['id'];
                }
            }
        }
        return null;
    }

    public function testConnection(): array
    {
        $res = $this->callApi('GET', 'health');
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'zPanel Hosting API bağlantısı başarılı.' : $res['message'],
        ];
    }

    public function createAccount(array $params = []): array
    {
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';
        $domain   = $params['domain'] ?? '';
        $email    = $params['user']['email'] ?? "{$username}@{$domain}";
        $fullName = trim(($params['user']['name'] ?? '') . ' ' . ($params['user']['surname'] ?? ''));

        // 1. Kullanıcı Hesabı Aç
        $custRes = $this->callApi('POST', 'customers', [
            'username'    => $username,
            'password'    => $password,
            'displayName' => $fullName ?: $username,
            'email'       => $email,
        ]);

        if (!$custRes['status']) {
            return ['status' => false, 'message' => $custRes['message'] ?: 'Kullanıcı açılamadı.'];
        }

        $customerId = $custRes['data']['customer']['id'] ?? $this->findCustomerId($username);

        // 2. Alan Adı Ekle
        if (!empty($domain) && !empty($customerId)) {
            $this->callApi('POST', 'domains', [
                'name'    => $domain,
                'ownerId' => $customerId,
                'kind'    => 'domain',
            ]);
        }

        return ['status' => true, 'message' => 'Hosting hesabı ve alan adı başarıyla açıldı.'];
    }

    public function suspendAccount(array $params = []): array
    {
        $username = $params['username'] ?? '';
        $customerId = $this->findCustomerId($username);
        if (!$customerId) {
            return ['status' => true, 'message' => 'Hesap bulunamadı.'];
        }

        $res = $this->callApi('PATCH', "customers/{$customerId}", ['status' => 'suspended']);
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'Hesap askıya alındı.' : $res['message'],
        ];
    }

    public function unsuspendAccount(array $params = []): array
    {
        $username = $params['username'] ?? '';
        $customerId = $this->findCustomerId($username);
        if (!$customerId) {
            return ['status' => false, 'message' => 'Hesap bulunamadı.'];
        }

        $res = $this->callApi('PATCH', "customers/{$customerId}", ['status' => 'active']);
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'Hesap aktif edildi.' : $res['message'],
        ];
    }

    public function terminateAccount(array $params = []): array
    {
        $username = $params['username'] ?? '';
        $customerId = $this->findCustomerId($username);
        if (!$customerId) {
            return ['status' => true, 'message' => 'Hesap zaten silinmiş.'];
        }

        $res = $this->callApi('DELETE', "customers/{$customerId}");
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'Hesap silindi.' : $res['message'],
        ];
    }

    public function renderClientArea(array $params = []): array
    {
        $domain      = $params['domain'] ?? '';
        $serverIp    = $params['server_ip'] ?? $this->server['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $isReseller  = !empty($params['is_reseller']);

        $panelPort   = $isReseller ? 8036 : 8026;
        $webmailPort = 8046;

        $ssoUrl      = !empty($panelHost) ? "https://{$panelHost}:{$panelPort}" : '#';
        $webmailUrl  = !empty($panelHost) ? "https://{$panelHost}:{$webmailPort}" : '#';

        return [
            'view' => 'modules.servers.zpanel_hosting.views.clientArea',
            'data' => [
                'domain'        => $domain,
                'username'      => $params['username'] ?? '',
                'password'      => $params['password'] ?? '',
                'server_ip'     => $serverIp,
                'panel_host'    => $panelHost,
                'panel_port'    => $panelPort,
                'webmail_port'  => $webmailPort,
                'is_reseller'   => $isReseller,
                'sso_url'       => $ssoUrl,
                'webmail_url'   => $webmailUrl,
                'package_name'  => $params['package_name'] ?? ($isReseller ? 'Bayi Hosting' : 'Limitsiz Hosting'),
            ],
        ];
    }
}
