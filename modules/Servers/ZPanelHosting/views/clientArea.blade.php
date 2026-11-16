{{-- zPanel Web Hosting & Reseller PNLCS Blade View --}}
<div class="zp-pnlcs-hosting">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $domain ?: $server_ip }}</h2>
                <div class="text-sm font-mono text-slate-500">
                    Sunucu IP: <span class="font-bold text-slate-700">{{ $server_ip ?: 'Atanmadı' }}</span> &bull; {{ $package_name }}
                </div>
            </div>
            <div>
                <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 text-white font-semibold px-5 py-2.5 rounded-full hover:bg-emerald-700 transition">
                    zPanel Paneline Giriş (:{{ $panel_port }}) &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- 1. Erişim Bilgileri --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <h3 class="text-base font-bold text-slate-900 mb-4">Hesap Giriş &amp; Erişim Bilgileri</h3>
        <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4 border-b border-slate-200">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Alan Adı</div>
                    <div class="font-bold font-mono text-slate-900">{{ $domain ?: '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Sunucu IP</div>
                    <div class="font-bold font-mono text-slate-900">{{ $server_ip ?: '-' }}</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 pb-4 border-b border-slate-200">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Kullanıcı Adı</div>
                    <div class="font-bold font-mono text-slate-900">{{ $username ?: '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Parola</div>
                    <div class="font-bold font-mono text-slate-900">••••••••••</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Panel Adresi</div>
                    <div class="font-mono text-sm text-blue-600">
                        @if(!empty($panel_host))
                            <a href="https://{{ $panel_host }}:{{ $panel_port }}" target="_blank">https://{{ $panel_host }}:{{ $panel_port }}</a>
                        @else
                            Kurulum bekleniyor...
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Webmail Adresi</div>
                    <div class="font-mono text-sm text-blue-600">
                        @if(!empty($panel_host))
                            <a href="https://{{ $panel_host }}:{{ $webmail_port }}" target="_blank">https://{{ $panel_host }}:{{ $webmail_port }}</a>
                        @else
                            Kurulum bekleniyor...
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. E-Posta Yönetimi --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-bold text-slate-900">E-Posta Yönetimi (Hesap Açma)</h3>
            <a href="{{ $webmail_url }}" target="_blank" class="text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-600 text-emerald-600 hover:bg-emerald-50">
                Webmail (:{{ $webmail_port }})
            </a>
        </div>
        <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
            <form class="flex flex-wrap md:flex-nowrap gap-3">
                <div class="flex-1 flex">
                    <input type="text" placeholder="adsoyad" class="border border-slate-300 rounded-l-lg px-3 py-2 text-sm w-full">
                    <span class="bg-slate-200 border border-slate-300 border-l-0 rounded-r-lg px-3 py-2 text-xs font-mono flex items-center text-slate-600">@{{ $domain ?: 'domain' }}</span>
                </div>
                <div class="flex-1">
                    <input type="password" placeholder="Parola" class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-full">
                </div>
                <div>
                    <button type="button" class="bg-slate-900 text-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-slate-800">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

