# Hướng dẫn cấu hình Pusher cho Chat Realtime

## 1. Cài đặt Pusher

### Bước 1: Đăng ký tài khoản Pusher

-   Truy cập https://pusher.com/
-   Đăng ký tài khoản miễn phí
-   Tạo một app mới

### Bước 2: Cài đặt package

```bash
composer require pusher/pusher-php-server
npm install laravel-echo pusher-js
```

### Bước 3: Cấu hình .env

Thêm các dòng sau vào file `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=your_cluster

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Bước 4: Build assets

```bash
npm run build
```

## 2. Cách hoạt động

### Event Broadcasting

-   Khi có tin nhắn mới, `MessageSent` event sẽ được dispatch
-   Event này sẽ broadcast đến các channel tương ứng:
    -   Private channel cho chat 1-1: `chat-user-{userId}`
    -   Public channel cho chat nhóm: `chat-class-{classId}`

### Frontend Listening

-   JavaScript sẽ listen các event từ Pusher
-   Khi nhận được tin nhắn mới, sẽ tự động cập nhật UI
-   Không cần refresh trang mỗi 10 giây nữa

## 3. Lợi ích

-   **Realtime**: Tin nhắn xuất hiện ngay lập tức
-   **Hiệu quả**: Không cần polling liên tục
-   **Tiết kiệm tài nguyên**: Giảm tải cho server
-   **Trải nghiệm người dùng tốt hơn**: Không có độ trễ

## 4. Troubleshooting

### Nếu không nhận được tin nhắn realtime:

1. Kiểm tra cấu hình Pusher trong .env
2. Đảm bảo đã build assets: `npm run build`
3. Kiểm tra console browser có lỗi gì không
4. Kiểm tra Pusher dashboard xem có event được gửi không

### Nếu có lỗi authorization:

1. Kiểm tra BroadcastServiceProvider đã được đăng ký
2. Kiểm tra routes/channels.php có đúng không
3. Đảm bảo user đã đăng nhập
