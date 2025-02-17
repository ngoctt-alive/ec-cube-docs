---
layout: single
title: Tùy chỉnh tính năng hạn chế tốc độ
keywords: core カスタマイズ スロットリング
tags: [core, throttling]
permalink: customize_throttling
folder: customize
---

---

## Chức năng giới hạn tốc độ (Throttling)  

※ Chức năng này có sẵn từ EC-CUBE 4.2.1.  

- [EC-CUBE/ec-cube#5881](https://github.com/EC-CUBE/ec-cube/pull/5881)  
- [EC-CUBE/ec-cube#5942](https://github.com/EC-CUBE/ec-cube/pull/5942)  

Chức năng giới hạn tốc độ đã được bổ sung để ngăn chặn các hành vi gian lận như Credit Master.  

Có thể thực hiện giới hạn theo địa chỉ IP hoặc theo tài khoản thành viên.  

Các chức năng bị áp dụng giới hạn và số lần thử như sau:  

| Màn hình | Đường dẫn | Theo IP | Theo tài khoản | Ghi chú |  
|---|---|---|---|---|  
| Đăng ký thành viên | entry | 25 lần/30 phút | - | Thực hiện khi chuyển từ màn hình nhập sang màn hình xác nhận trong quá trình đăng ký tài khoản mới |  
| Đăng ký thành viên (hoàn tất) | entry | 5 lần/30 phút | - | Thực hiện khi hoàn tất đăng ký tài khoản mới |  
| Cấp lại mật khẩu | forgot | 5 lần/30 phút | - | - |  
| Liên hệ | contact | 5 lần/30 phút | - | - |  
| Xác nhận đơn hàng | shopping_confirm | 25 lần/30 phút | 10 lần/30 phút | Thực hiện ngay trước khi chuyển xử lý sang plugin thanh toán sau khi hoàn tất xác nhận |  
| Hoàn tất đơn hàng | shopping_checkout | 25 lần/30 phút | 10 lần/30 phút | Thực hiện ngay trước khi chuyển xử lý sang plugin thanh toán sau khi hoàn tất xác nhận |  
| Chỉnh sửa thông tin thành viên | mypage_change | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Thêm địa chỉ nhận hàng | mypage_delivery_new | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Chỉnh sửa địa chỉ nhận hàng | mypage_delivery_edit | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Xóa địa chỉ nhận hàng | mypage_delivery_delete | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Cấu hình giao hàng nhiều địa chỉ trong giỏ hàng | shopping_shipping_multiple_edit | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Chỉnh sửa địa chỉ nhận hàng trong giỏ hàng | shopping_shipping_edit | - | 10 lần/30 phút | Chỉ áp dụng cho thành viên đã đăng nhập |  
| Đăng nhập quản trị (Xác thực hai bước) | admin_two_factor_auth | - | 5 lần/30 phút | Chỉ áp dụng cho quản trị viên sau khi đăng nhập vào màn hình quản trị |  

Chức năng này cũng có thể được sử dụng từ Plugin và Customize.  

## Mẫu mở rộng  

### Cấu hình bằng YAML  

Có thể thiết lập giới hạn tốc độ cho các đường dẫn bằng cách cấu hình YAML như sau.  

Giới hạn có thể được thiết lập theo IP hoặc theo tài khoản (thành viên hoặc quản trị viên).  

Tệp YAML cần được đặt trong:  

- Trường hợp Customize: `app/Customize/Resource/config/services.yaml`  
- Trường hợp Plugin: `app/Plugin/[Plugin Code]/Resource/config/services.yaml`  

Ví dụ:  

```yaml
eccube:
    rate_limiter:
        forgot:
            route: forgot # Chỉ định đường dẫn áp dụng giới hạn tốc độ.
            method: ['POST'] # Chỉ định phương thức HTTP bị giới hạn. Mặc định là POST.
            type: ip # Phương thức kiểm soát giới hạn (ip, user). Có thể chỉ định nhiều phương thức.
            limit: 5
            interval: '30 minutes'
        entry:
            route: entry
            method: ['POST']
            params:
                mode: complete # Chỉ định tham số điều hướng trong trường hợp có màn hình xác nhận.
            type: [ 'ip', 'user' ]
            limit: 5
            interval: '30 minutes'
        shopping_confirm_ip:
            route: ~ # Nếu route là null, giới hạn tốc độ sẽ không được tự động áp dụng.
            limit: 25
            interval: '30 minutes'
```  

### Nếu muốn tích hợp giới hạn tốc độ tùy chỉnh  

Có thể đặt route thành null để không thực hiện tự động mà chỉ tạo `RateLimiter`.  

```yaml
eccube:
    rate_limiter:
        custom:
            route: ~ 
            limit: 25
            interval: '30 minutes'
```  

Sau đó, có thể sử dụng `RateLimiterFactory` trong Controller như sau:  

```php
class CustomController {

  public function index(RateLimiterFactory $customLimiter, Request $request) {
    $limiter = $customLimiter->create($request->getClientIp());
    if (!$limiter->consume()->isAccepted()) {
        throw new TooManyRequestsHttpException();
    }
  }
}
```  

### Ghi đè thiết lập có sẵn  

Các thiết lập của hệ thống hoặc plugin có thể được ghi đè bằng Customize.  

Ví dụ, nếu hệ thống có thiết lập như sau:  

```yaml
eccube:
 rate_limiter:
  forgot:
   route: forgot
   method: ['POST']
   type: ip
   limit: 5
   interval: '30 minutes'
```  

Có thể ghi đè bằng Customize như sau:  

```yaml
eccube:
 rate_limiter:
  forgot:
   limit: 10
   interval: '60 minutes'
```  

### Xóa dữ liệu giới hạn tốc độ  

Có thể xóa dữ liệu giới hạn tốc độ bằng lệnh sau:  

```
bin/console cache:pool:clear rate_limiter.cache --env=<APP_ENV> 
```  

### Thay đổi nơi lưu trữ thông tin giới hạn tốc độ  

Mặc định, thông tin giới hạn tốc độ được lưu trong hệ thống tệp.  

Có thể thay đổi nơi lưu trữ bằng cách chỉnh sửa `app/config/eccube/packages/rate_limiter.yml`:  

```yaml
# config/packages/rate_limiter.yaml
framework:
    cache:
        pools:
            rate_limiter.cache:
                adapter: cache.adapter.filesystem
```  

Tham khảo thêm tại: [Symfony Cache](https://symfony.com/doc/5.4/cache.html)  

{: .notice--danger}  
Hiện tại, do lỗi [EC-CUBE/ec-cube#5957](https://github.com/EC-CUBE/ec-cube/issues/5957), không thể chọn `cache.adapter.doctrine_dbal` làm nơi lưu trữ thông tin giới hạn tốc độ.  
Nếu sử dụng hệ thống phân tán, hãy cấu hình Redis hoặc Memcached.  
{: .notice--danger}  

### Giới hạn tốc độ khi đăng nhập  

Để biết thêm chi tiết về giới hạn tốc độ khi đăng nhập, vui lòng tham khảo:  

- [EC-CUBE/ec-cube#4249](https://github.com/EC-CUBE/ec-cube/issues/4249)  
- [EC-CUBE/ec-cube#5473](https://github.com/EC-CUBE/ec-cube/pull/5473)  
- [EC-CUBE/ec-cube#6035](https://github.com/EC-CUBE/ec-cube/pull/6035)  
- [EC-CUBE/ec-cube#6038](https://github.com/EC-CUBE/ec-cube/pull/6038)  

## Thông tin tham khảo  

Chức năng giới hạn tốc độ sử dụng `symfony/rate-limiter`.  
Xem tài liệu Symfony để biết thêm cách tùy chỉnh:  

[Rate Limiter](https://symfony.com/doc/5.4/rate_limiter.html)