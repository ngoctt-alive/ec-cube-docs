---
title: Mở rộng với tính năng Symfony
keywords: core カスタマイズ Symfony
tags: [core, symfony]
permalink: customize_symfony
folder: customize
---

## Tổng quan  

EC-CUBE được phát triển dựa trên Symfony và Doctrine.  
Vì vậy, có thể tận dụng các cơ chế mở rộng do Symfony và Doctrine cung cấp.  

Bài viết này sẽ giới thiệu một số cơ chế mở rộng tiêu biểu và cách triển khai chúng.  

---

## Sự kiện Symfony  

Có thể sử dụng hệ thống sự kiện của Symfony.  

### Tạo một Event Listener hiển thị "hello world"  

Tạo tệp `HelloListener.php` trong thư mục `app/Customize/EventListener`.  

```php
<?php

namespace Customize\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HelloListener implements EventSubscriberInterface
{
    public function onResponse(FilterResponseEvent $event)
    {
        echo 'hello world';
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }
}
```

Sau khi tạo, nếu "hello world" xuất hiện trên bất kỳ trang nào thì đã thành công.  

Nếu không hiển thị, hãy xóa bộ nhớ đệm bằng lệnh:  

```bash
bin/console cache:clear --no-warmup
```

Bạn cũng có thể kiểm tra các Event Listener đã đăng ký bằng lệnh:  

```bash
bin/console debug:event-dispatcher
```

**Tham khảo:**  

- [The HttpKernel Component](https://symfony.com/doc/current/components/http_kernel.html)  
- [Events and Event Listeners](https://symfony.com/doc/current/event_dispatcher.html)  
- [Built-in Symfony Events](https://symfony.com/doc/current/reference/events.html)  

---

## Command  

Có thể tạo các lệnh console có thể chạy bằng `bin/console`.  

### Tạo một lệnh hiển thị "hello world"  

Tạo tệp `HelloCommand.php` trong thư mục `app/Customize/Command`.  

```php
<?php

namespace Customize\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloCommand extends Command
{
    // Tên lệnh
    protected static $defaultName = 'acme:hello';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Hiển thị "hello world"
        $io->success('hello world');
    }
}
```

- `$defaultName` xác định tên lệnh.  
- `$io->success('hello world')` hiển thị "hello world".  

Sau khi tạo, có thể thực thi bằng lệnh sau:  

```bash
bin/console acme:hello
```

Kết quả đầu ra:  

```
 [OK] hello world
```

Nếu lệnh không được nhận diện, hãy xóa bộ nhớ đệm bằng lệnh:  

```bash
bin/console cache:clear --no-warmup
```

**Tham khảo:**  

- [Console Commands](https://symfony.com/doc/current/console.html)  

---

## Sự kiện Doctrine  

Có thể sử dụng hệ thống sự kiện của Doctrine.  

### Tạo một Event Listener thêm chuỗi "Chào mừng đến với" vào tên cửa hàng  

Tạo tệp `HelloEventSubscriber.php` trong thư mục `app/Customize/Doctrine/EventSubscriber`.  

```php
<?php

namespace Customize\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Eccube\Entity\BaseInfo;

class HelloEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::postLoad];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof BaseInfo) {
            $shopName = $entity->getShopName();
            $shopName = 'Chào mừng đến với ' . $shopName;
            $entity->setShopName($shopName);
        }
    }
}
```

Sau khi tạo, mở trang chủ và nếu hiển thị `"Chào mừng đến với [Tên cửa hàng]"` thì đã thành công.  

Nếu không hiển thị, hãy xóa bộ nhớ đệm bằng lệnh:  

```bash
bin/console cache:clear --no-warmup
```

**Tham khảo:**  

- [The Event System](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html)  
- [Doctrine Event Listeners and Subscribers](https://symfony.com/doc/current/doctrine/event_listeners_subscribers.html)  

Lưu ý: Symfony cung cấp cách đăng ký sự kiện trong `services.yaml`, nhưng EC-CUBE tự động đăng ký Event Listener vào container, nên không cần cấu hình trong `services.yaml`.  

---

## Sử dụng Symfony Bundle  

(TODO)  