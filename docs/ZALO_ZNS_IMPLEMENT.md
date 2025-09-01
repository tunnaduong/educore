# Tích Hợp OneSMS Zalo API vào EduCore

Tài liệu này hướng dẫn cách tích hợp **OneSMS Gateway API** để gửi tin nhắn Zalo OA (Zalo Official Account) trong hệ thống **EduCore**.

---

## 1. Giới thiệu

-   OneSMS Gateway (Conek Telecom) cung cấp dịch vụ gửi tin nhắn thương hiệu qua Zalo OA.
-   EduCore sẽ sử dụng API này để gửi thông báo: OTP, nhắc lịch học, báo kết quả, xác nhận thanh toán... đến học viên.

---

## 2. Endpoint chính

**SendSMSZalo** – Gửi tin nhắn qua Zalo (hoặc fallback SMS).

````

POST [https://zaloapi.conek.vn/SendSMSZalo](https://zaloapi.conek.vn/SendSMSZalo?utm_source=chatgpt.com)
Content-Type: application/json

```bash
---

## 3. Request Body
```jsonc
{
  "id": "uuid_hoac_ma_giao_dich",
  "username": "your_username",
  "password": "your_password",
  "brandname": "EduCore",
  "message_sms": "Tin nhắn fallback SMS",
  "phone": "0987654321",
  "template_id": "123456",
  "template_data": {
    "param1": "Giá trị 1",
    "param2": "Giá trị 2"
  },
  "type_send": 2,
  "resend_sms": 1,
  "brandname_sms": "EduCore",
  "unicode": 0
}
````

### Giải thích các tham số

-   `id`: ID duy nhất cho mỗi request (UUID hoặc mã giao dịch).
-   `username` / `password`: tài khoản API do OneSMS cấp.
-   `brandname`: tên OA đã đăng ký.
-   `phone`: số điện thoại người nhận.
-   `template_id`: ID template tin nhắn đã duyệt.
-   `template_data`: dữ liệu điền vào template.
-   `type_send`:

    -   `1`: gửi Follower
    -   `2`: gửi ZNS
    -   `3`: gửi Follower → fallback ZNS

-   `resend_sms`:

    -   `0`: không fallback SMS
    -   `1`: fallback SMS nếu gửi Zalo thất bại

-   `unicode`: `0` gửi không dấu, `1` gửi có dấu (chỉ áp dụng cho SMS fallback).

---

## 4\. Response

```json
{
    "phone": "0987654321",
    "code": "0",
    "message": "Success"
}
```

### Bảng mã trả về

-   `0`: Thành công
-   `1`: Lỗi hệ thống
-   `2`: Input invalid
-   `3`: Sai username/password
-   `7`: Hết quota
-   `8`: Template chưa khai báo
-   `9`: Dữ liệu không khớp template
-   `…` (xem thêm trong tài liệu gốc)

---

## 5\. Lệnh cURL mẫu

```bash
curl -X POST "https://zaloapi.conek.vn/SendSMSZalo" \
  -H "Content-Type: application/json" \
  -d '{
    "id": "order_20250828_001",
    "username": "username",
    "password": "password",
    "brandname": "EduCore",
    "message_sms": "fallback SMS message",
    "phone": "0987654321",
    "template_id": "123456",
    "template_data": {
      "student": "Nguyen Van A",
      "class": "HSK1"
    },
    "type_send": 2,
    "resend_sms": 1,
    "brandname_sms": "EduCore",
    "unicode": 0
  }'
```

---

## 6\. Tích hợp trong Laravel (EduCore)

Ví dụ code gọi API từ **Laravel Service**:

```php
use Illuminate\Support\Facades\Http;

class OneSmsService
{
    protected string $endpoint = 'https://zaloapi.conek.vn/SendSMSZalo';

    public function sendZalo($phone, $templateId, $templateData, $id = null)
    {
        $payload = [
            'id'            => $id ?? uniqid('ec_', true),
            'username'      => config('services.onesms.username'),
            'password'      => config('services.onesms.password'),
            'brandname'     => 'EduCore',
            'phone'         => $phone,
            'template_id'   => $templateId,
            'template_data' => $templateData,
            'type_send'     => 2,
            'resend_sms'    => 1,
            'brandname_sms' => 'EduCore',
            'unicode'       => 0,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->endpoint, $payload);

        return $response->json();
    }
}
```

### Cấu hình trong `.env`

```env
ONESMS_USERNAME=your_username
ONESMS_PASSWORD=your_password
```

### Cấu hình trong `config/services.php`

```php
'onesms' => [
    'username' => env('ONESMS_USERNAME'),
    'password' => env('ONESMS_PASSWORD'),
],
```

---

## 7\. Các API bổ sung

-   **GetOaZalo** – Lấy danh sách OA.
-   **GetTemplateZaloByOa** – Lấy chi tiết template.
-   **ReceiveMessageZalo** – Lấy báo cáo trạng thái tin.
-   **Webhook** – OneSMS trả trạng thái tự động về URL callback của EduCore.

---

## 8\. Lưu ý khi tích hợp

-   Mỗi `id` request phải **duy nhất**.
-   Tin Zalo giới hạn **400 ký tự**.
-   Quota tin nhắn do OneSMS cấp, hết quota sẽ không gửi được.
-   Một số template không được gửi ban đêm (22h–6h).
-   OTP sẽ được ưu tiên gửi nhanh hơn.
