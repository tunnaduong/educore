# Hướng dẫn tích hợp WebHooks | SePay

**Bước 1:** Truy cập vào menu [WebHooks](https://my.sepay.vn/webhooks)

**Bước 2:** Chọn vào button \+ Thêm webhooks ở phía trên, bên phải

![](assets/img/others/webhook-add.png?v=3)

**Bước 3:** Điền đầy đủ thông tin, bao gồm:

- **Đặt tên:** Bạn đặt tên bất kỳ
- 1 **Chọn sự kiện**: Bạn có thể chọn sự kiện **Bắn WebHooks khi** `Có tiền vào`, `Có tiền ra` hoặc `Cả hai`
- 2 **Chọn điều kiện**: Bao gồm:
  - **Khi tài khoản ngân hàng là**: Chọn tài khoản mà khi có giao dịch, webhooks sẽ bắn. Trong trường hợp bạn muốn chỉ định các tài khoản ảo (VA) cụ thể để nhận thông báo, tích vào hộp kiểm **Lọc theo tài khoản ảo** và chọn các tài khoản ảo cần theo dõi.
  - **Bỏ qua nếu nội dung giao dịch không có Code thanh toán?**: Nếu bạn chọn Có, SePay sẽ KHÔNG bắn webhooks nếu không nhận diện được code thanh toán trong nội dung thanh toán.  
    TIP: Cấu hình nhận diện code thanh toán tại phần **Công ty** -> **Cấu hình chung** -> **Cấu trúc mã thanh toán**
- 3 **Thuộc tính WebHooks**: Bao gồm:
  - **Gọi đến URL**: Đường link mà bạn muốn gọi WebHooks. Nếu muốn lập trình website nhận webhooks, xem hướng dẫn [tại đây](lap-trinh-webhooks.html).
  - **Là WebHooks xác thực thanh toán?**: Chọn Đúng nếu webhooks này dùng để xác thực thanh toán cho website/ ứng dụng bán hàng.
  - **Gọi lại WebHooks khi?**: Hiện tại SePay hỗ trợ một số điều kiện để bạn có thể gọi lại Webhooks tự động:
    - HTTP Status Code không nằm trong phạm vi từ 200 đến 299.
- 4 **Cấu hình chứng thực WebHooks**: Bao gồm:
  - **Kiểu chứng thực**: Hiện SePay hỗ trợ chứng thực với **OAuth 2.0**, **API Key** hoặc **Không cần chứng thực**.
    - Nếu chọn **OAuth 2.0**, bạn cần điền đầy đủ thông tin như OAuth 2.0 Access Token URL, OAuth 2.0 Client Id, OAuth 2.0 Client Secret
    - Nếu chọn **Không chứng thực** hoặc **Api Key**, bạn cần chọn Request Content type là **application/json**, **multipart/form-data** hoặc **application/x-www-form-urlencoded** tùy theo ứng dụng nhận webhooks của mình.

**Bước 4:** Chọn **Thêm** để hoàn tất tích hợp.

### Dữ liệu gửi qua webhooks[](#du-lieu)

SePay sẽ gửi một request với phương thức là **POST**, với nội dung gửi như sau:

```
{
    "id": 92704,                              // ID giao dịch trên SePay
    "gateway":"Vietcombank",                  // Brand name của ngân hàng
    "transactionDate":"2023-03-25 14:02:37",  // Thời gian xảy ra giao dịch phía ngân hàng
    "accountNumber":"0123499999",              // Số tài khoản ngân hàng
    "code":null,                               // Mã code thanh toán (sepay tự nhận diện dựa vào cấu hình tại Công ty -> Cấu hình chung)
    "content":"chuyen tien mua iphone",        // Nội dung chuyển khoản
    "transferType":"in",                       // Loại giao dịch. in là tiền vào, out là tiền ra
    "transferAmount":2277000,                  // Số tiền giao dịch
    "accumulated":19077000,                    // Số dư tài khoản (lũy kế)
    "subAccount":null,                         // Tài khoản ngân hàng phụ (tài khoản định danh),
    "referenceCode":"MBVCB.3278907687",         // Mã tham chiếu của tin nhắn sms
    "description":""                           // Toàn bộ nội dung tin nhắn sms
}

```

### Kiểm tra hoạt động[](#kiem-tra)

Nếu sử dụng tài khoản Demo, bạn vào menu [Giao dịch](https://my.sepay.vn/transactions) -> Giả lập giao dịch để thêm một giao dịch mới. Xem thông tin về Giả lập giao dịch [tại đây](gia-lap-giao-dich.html)

Nếu không sử dụng tài khoản demo, bạn có thể thử chuyển một khoản tiền nhỏ vào để có giao dịch thử nghiệm.

Sau đó vào menu **Nhật ký** -> [Nhật ký webhooks](https://my.sepay.vn/webhookslog) để xem danh sách các webhooks đã bắn.

Ngoài ra, bạn có thể xem tin nhắn webhooks đã gửi theo từng giao dịch tại menu [Giao dịch](https://my.sepay.vn/transactions) -> cột Tự động -> chọn vào **Pay**

### Nhận diện webhooks thành công[](#nhan-dien)

Khi nhận webhooks gọi từ SePay, website của bạn cần phản hồi (response) theo quy ước sau để SePay biết được kết quả là thành công:

- Với chứng thực OAuth 2.0

- Nội dung trả về là json có **success: true**: `{"success": true, ....}`
- HTTP Status Code phải là 201

- Với chứng thực API Key

- Nội dung trả về là json có **success: true**: `{"success": true, ....}`
- HTTP Status Code phải là 201 hoặc 200

- Với không chứng thực

- Nội dung trả về là json có **success: true**: `{"success": true, ....}`
- HTTP Status Code phải là 201 hoặc 200

Nếu kết quả trả về không thỏa mãn các điều kiện trên, SePay sẽ xem là webhook thất bại.

### Retry webhooks tự động[](#retry)

SePay sẽ gọi lại webhooks nếu trạng thái **kết nối mạng** đến webhook url thất bại. Ngoài ra, bạn có thể tùy chọn các điều kiện SePay hỗ trợ sẵn để có thể gọi lại webhooks. Thời gian gọi cách nhau bằng phút, tăng dần theo dãy số [Fibonacci](https://en.wikipedia.org/wiki/Fibonacci_sequence)

- Số lần gọi lại tối đa là 7 lần
- Tối đa là 5 giờ kể từ khi gọi lần đầu thất bại
- Network connect timeout của SePay là 5 giây
- Thời gian chờ phản hồi tối đa của SePay là 8 giây

### Yêu cầu chống trùng lặp giao dịch[](#duplicate-checking-requirement)

Để tránh trùng lặp giao dịch khi phát sinh các sự cố với kết nối webhook của phía người dùng tại cơ chế retry. SePay khuyến nghị người dùng xử lý chống trùng lặp giao dịch khi nhận thông báo biến động giao dịch từ SePay thông qua webhook của mình.

Người dùng cần kiểm tra tính duy nhất của trường `id`, hoặc kết hợp thêm các trường khác như `referenceCode`, `transferType`, `transferAmount` từ dữ liệu SePay gửi qua webhook để đảm bảo tính duy nhất của giao dịch.

### Retry webhooks bằng tay[](#retry-manual)

Bạn có thể thao tác gọi lại webhooks bằng tay bằng cách vào Chi tiết một giao dịch -> Chọn Xem tại Webhooks đã bắn -> Gọi lại. Hoặc vào phần Nhật ký Webhooks, chọn vào Gọi lại

![](assets/img/others/webhook-log-show.png)
