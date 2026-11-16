# zPanel Web Hosting & Reseller Modülü API Dokümantasyonu (PNLCS)

zPanel Hosting modülü, PNLCS (Laravel 13) altyapısında zPanel Core REST API (:8056) ile çalışır. Web sunucusu olarak **zMaxServer (OpenLiteSpeed & LSPHP / LSAPI)** kullanılmaktadır.

---

## 1. Port Haritası

- **8026 / TCP (HTTPS):** Müşteri Web Kontrol Paneli
- **8036 / TCP (HTTPS):** Bayi / Yönetici Paneli
- **8046 / TCP (HTTPS):** Webmail Servisi
- **8056 / TCP (HTTPS):** Core REST API (PNLCS Sunucu Otomasyonu)

---

## 2. API Yetkilendirme

```http
Authorization: Bearer <server_api_key>
Content-Type: application/json
Accept: application/json
```

---

## 3. Desteklenen PNLCS Metotları ve API Uç Noktaları

| PNLCS Metodu | HTTP Yöntemi | zPanel Core API Uç Noktası | Açıklama |
|---|---|---|---|
| `testConnection()` | `GET` | `/v1/health` | Sunucu ve API erişilebilirlik testi |
| `createAccount()` | `POST` | `/v1/customers` & `/v1/domains` | Hesap ve birincil alan adı oluşturma |
| `suspendAccount()` | `PATCH` | `/v1/customers/:id` | Hesabı askıya alma (`status: suspended`) |
| `unsuspendAccount()` | `PATCH` | `/v1/customers/:id` | Hesabı aktif etme (`status: active`) |
| `terminateAccount()` | `DELETE` | `/v1/customers/:id` | Hesabı ve alan adlarını silme |
| `changePassword()` | `PATCH` | `/v1/customers/:id` | Müşteri şifresini güncelleme |
| `changePackage()` | `PATCH` | `/v1/customers/:id` | Paket, disk ve trafik kotasını güncelleme |
| `clientArea()` | Blade | `views/clientArea.blade.php` | Müşteri paneli detay & hızlı erişim görünümü |
