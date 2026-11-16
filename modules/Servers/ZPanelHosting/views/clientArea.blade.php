{{-- zPanel Web Hosting & Reseller PNLCS Blade View --}}
{{-- Port Mimarisi: 8026 (Client), 8036 (Root/Bayi), 8046 (Webmail), 8056 (API) --}}

<div class="zp-pnlcs-hosting" style="font-family: inherit;">
    <!-- Üst Bilgi Kartı -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-slate-900">{{ $domain ?: $server_ip }}</h2>
                    <span class="bg-sky-100 text-sky-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">zPanel v1.0</span>
                </div>
                <div class="text-sm font-mono text-slate-500">
                    Sunucu IP: <span class="font-bold text-slate-900">{{ $server_ip ?: 'Atanmadı' }}</span> &bull; {{ $package_name ?? 'Web Hosting' }}
                </div>
            </div>
            <div>
                <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    zPanel Giriş (:{{ $panel_port ?? 8026 }}) &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 1. Erişim & Hesap Bilgileri -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-base font-bold text-slate-900 mb-4">Hesap Giriş &amp; Erişim Bilgileri</h3>
        <div class="bg-slate-50 rounded-lg border border-slate-200 p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4 border-b border-slate-200">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Barındırılan Alan Adı</div>
                    <div class="font-bold font-mono text-slate-900 text-base">{{ $domain ?: '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Sunucu Statik IP</div>
                    <div class="font-bold font-mono text-slate-900 text-base">{{ $server_ip ?: '-' }}</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 pb-4 border-b border-slate-200">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Kullanıcı Adı (FTP &amp; Panel)</div>
                    <div class="font-bold font-mono text-slate-900 text-base">{{ $username ?: '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Hesap Şifresi</div>
                    <div class="font-bold font-mono text-slate-900 text-base">••••••••••••</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Panel Portu</div>
                    <div class="font-mono text-sm">
                        <a href="https://{{ $panel_host ?? $server_ip }}:{{ $panel_port ?? 8026 }}" target="_blank" class="text-blue-600 font-semibold hover:underline">
                            https://{{ $panel_host ?? $server_ip }}:{{ $panel_port ?? 8026 }}
                        </a>
                    </div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase mb-1">Webmail Portu</div>
                    <div class="font-mono text-sm">
                        <a href="https://{{ $panel_host ?? $server_ip }}:{{ $webmail_port ?? 8046 }}" target="_blank" class="text-blue-600 font-semibold hover:underline">
                            https://{{ $panel_host ?? $server_ip }}:{{ $webmail_port ?? 8046 }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Hızlı Servis Erişimi -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-base font-bold text-slate-900 mb-4">Hızlı Servis Erişimi</h3>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-50 text-slate-900 border border-slate-300 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-100 transition">
                <span>&#x1F4C1;</span> Dosya Yöneticisi
            </a>
            <a href="https://{{ $panel_host ?? $server_ip }}:{{ $webmail_port ?? 8046 }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-50 text-slate-900 border border-slate-300 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-100 transition">
                <span>&#x2709;</span> Webmail Gelen Kutusu (:{{ $webmail_port ?? 8046 }})
            </a>
            <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-50 text-slate-900 border border-slate-300 px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-100 transition">
                <span>&#x1F5C3;</span> Veritabanları (MariaDB)
            </a>
        </div>
    </div>

    @if(!empty($is_reseller))
    <!-- 3. Bayi (Reseller) Yönetim Paneli -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-3 flex-wrap gap-3">
            <h3 class="text-base font-bold text-slate-900">Bayi (Reseller) Yönetim Paneli</h3>
            <a href="https://{{ $panel_host ?? $server_ip }}:8036" target="_blank" class="bg-slate-900 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                Bayi Paneline Git (:8036) &rarr;
            </a>
        </div>
        <p class="text-sm text-slate-500">
            Alt müşterileriniz için yeni web barındırma hesapları tanımlayabilir, disk/trafik kotalarını belirleyebilir ve hesapları zPanel Bayi Portu (8036) üzerinden doğrudan yönetebilirsiniz.
        </p>
    </div>
    @endif
</div>
