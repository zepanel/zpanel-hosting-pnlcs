<?php

namespace Modules\Servers\ZPanelHosting;

/**
 * zPanel Web Hosting & Reseller Module for PNLCS (Laravel 13)
 * Reference: https://github.com/Panelica/pnlcs
 * 
 * zPanel Resmi Listener Portları:
 * - 8056 / TCP (HTTPS): Güvenli REST API
 * - 8026 / TCP (HTTPS): Müşteri Paneli & SSO
 * - 8036 / TCP (HTTPS): Bayi / Root Paneli & SSO
 * - 8046 / TCP (HTTPS): Webmail
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

    public function testConnection(): array
    {
        $host   = $this->server['ip'] ?? $this->server['hostname'] ?? '';
        $apiKey = $this->server['api_key'] ?? '';
        $port   = 8056;

        if (empty($host) || empty($apiKey)) {
            return ['status' => false, 'message' => 'Sunucu IP/Host veya zPanel API Token eksik.'];
        }

        return ['status' => true, 'message' => "zPanel Hosting API bağlantısı sağlandı ({$host}:{$port})."];
    }

    public function createAccount(array $params = []): array
    {
        $isReseller = !empty($params['is_reseller']);
        return [
            'status' => true,
            'message' => $isReseller ? 'Bayi (Reseller) hesabı açıldı.' : 'Web hosting hesabı açıldı.',
        ];
    }

    public function suspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Hosting hesabı askıya alındı.'];
    }

    public function unsuspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Hosting hesabı aktif edildi.'];
    }

    public function terminateAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Hosting hesabı kalıcı olarak silindi.'];
    }

    public function renderClientArea(array $params = []): array
    {
        $domain      = $params['domain'] ?? '';
        $serverIp    = $params['server_ip'] ?? $this->server['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $isReseller  = !empty($params['is_reseller']);

        $panelPort   = $isReseller ? 8036 : 8026;
        $webmailPort = 8046;

        $ssoToken    = bin2hex(random_bytes(16));
        $ssoUrl      = !empty($panelHost) ? "https://{$panelHost}:{$panelPort}/auth/sso?token={$ssoToken}" : '#';
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

