---
title: Cấu trúc thư mục
keywords: spec directory structure
tags: [spec, getting_started]
permalink: spec_directory-structure

layout: single
---

## Đặc điểm

1. Dựa trên [Cấu trúc thư mục của Symfony3](https://symfony.com/doc/3.4/quick_tour/the_architecture.html){:target="_blank"}, EC-CUBE 4 có cấu trúc riêng biệt.

## Các thư mục chính và vai trò

### app/

- Đặt các tệp thay đổi cho mỗi ứng dụng, như tệp cấu hình, plugin và mã PHP tùy chỉnh EC-CUBE

```
app
├── Customize   Đặt mã PHP tùy chỉnh
├── Plugin      Đặt các plugin đã cài đặt
├── PluginData  Đặt các tệp sử dụng bởi plugin
├── config      Đặt tệp cấu hình
├── proxy       Đặt các lớp Proxy được tạo bởi tính năng mở rộng Entity
└── template    Đặt các tệp mẫu bị ghi đè
```

### bin/

- Đặt các tệp thực thi sử dụng trong phát triển, như `bin/console`

### html/

- Đặt các tệp tài nguyên (như tệp js, css và hình ảnh)

### src/

- Đặt các tệp PHP và Twig của phần cốt lõi EC-CUBE

### tests/

- Đặt mã kiểm tra

### var/

- Đặt các tệp được tạo ra trong quá trình chạy, như bộ nhớ cache và tệp nhật ký

### vendor/

- Đặt các thư viện phụ thuộc của bên thứ ba
