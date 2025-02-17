---
layout: single
title: Tùy chỉnh Controller
keywords: core カスタマイズ コントローラ
tags: [core, controller]
permalink: customize_controller
folder: customize
---

## Thêm Routing Mới

Bằng cách đặt các tệp lớp có chú thích `@Route` vào thư mục `./app/Customize/Controller/`, bạn có thể thêm các routing mới vào trang web.

Dưới đây là ví dụ đơn giản nhất về cách thêm routing. Khi truy cập vào `http://SiteURL/sample`, routing này sẽ hiển thị "Hello sample page !".

### Tệp Controller

./app/Customize/Controller/SamplePageController.php

```php
<?php

namespace Customize\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SamplePageController
{
    /**
     * @Method("GET")
     * @Route("/sample")
     */
    public function testMethod()
    {
        return new Response('Hello sample page !');
    }
}
```

## Sử dụng Tệp Template

Bằng cách sử dụng `@Template`, bạn có thể sử dụng các tệp template của Twig. Dưới đây là ví dụ, khi truy cập `http://SiteURL/sample`, nó sẽ hiển thị "Hello EC-CUBE !".

### Tệp Controller

```php
<?php

namespace Customize\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class SamplePageController
{
    /**
     * @Method("GET")
     * @Route("/sample")
     * @Template("Sample/index.twig")
     */
    public function testMethod()
    {
        return ['name' => 'EC-CUBE'];
    }
}
```

### Tệp Twig

./app/template/default/Sample/index.twig

```twig

<h3>Hello {{ name }} !</h3>

```

## Mẹo Tùy chỉnh

### Nhận Tham số từ URL

Bạn có thể nhận tham số chứa trong URL như một giá trị biến, ví dụ `http://SiteURL/sample/1`. Phần `{id}` trong `@Route` sẽ nhận được giá trị như một biến có tên `$id`.

```php
    /**
     * @Method("GET")
     * @Route("/sample/{id}")
     */
    public function testMethod($id)
    {
        return new Response('Parameter is '.$id);
    }
```

### Liên kết đến Routing đã thêm

Để liên kết đến routing đã thêm từ tệp template của trang khác, bạn cần đặt tên cho routing. Bạn có thể đặt tên bằng cách thêm tham số `name` vào chú thích `@Route`.

```php
    /**
     * @Method("GET")
     * @Route("/sample/{id}", name="sample_page")
     */
    public function testMethod($id)
    {
        return new Response('Parameter is '.$id);
    }
```

Khi liên kết từ các tệp template của trang khác, bạn có thể viết như sau. Bạn cũng có thể truyền tham số.

```twig
{% raw %}
<a href="{{ url("sample_page", { id : 2}) }}">Liên kết đến Sample trang</a>
{% endraw %}
```

### Ghi đè Routing có sẵn của EC-CUBE

Để ghi đè routing có sẵn của EC-CUBE, bạn cần định nghĩa routing với cùng đường dẫn và tên. Ví dụ dưới đây ghi đè trang "Về chúng tôi".

```php
    /**
     * @Method("GET")
     * @Route("/help/about", name="help_about")
     */
    public function testMethod()
    {
        return new Response('Overwrite /help/about page');
    }
```

### Thêm Routing cho Trang Quản trị

Nếu bạn muốn thêm routing chỉ có người dùng đã đăng nhập vào trang quản trị mới có thể truy cập, hãy sử dụng `/%eccube_admin_route%` trong đường dẫn.

```php
    /**
     * @Method("GET")
     * @Route("/%eccube_admin_route%/sample")
     */
    public function testMethod()
    {
        return new Response('admin page');
    }
```

Tương tự, routing đến UserData sử dụng `/%eccube_user_data_route%`.

### Thực hiện Redirect

Bằng cách kế thừa `AbstractController` và sử dụng hàm `redirectToRoute`, bạn có thể thực hiện redirect. Ví dụ dưới đây, khi có truy cập, sẽ thực hiện redirect đến trang "Về chúng tôi".

```php
    /**
     * @Method("GET")
     * @Route("/sample")
     */
    public function testMethod()
    {
        return $this->redirectToRoute('help_about');
    }
```

Ngoài ra, bạn cũng có thể sử dụng hàm `forwardToRoute` để chuyển xử lý đến controller khác thay vì redirect.

### Sử dụng Dịch vụ trong Controller

Bằng cách kế thừa `AbstractController`, bạn có thể sử dụng các dịch vụ thường xuyên sử dụng. Ví dụ dưới đây, sử dụng `EntityManager` để lấy Entity của sản phẩm.

```php
<?php

namespace Customize\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SamplePageController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/sample")
     */
    public function testMethod()
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository('Eccube\Entity\Product')->find(1);

        return new Response('Product is '.$product->getName());
    }
}
```

Ngoài `EntityManager`, bạn có thể xem các dịch vụ có thể sử dụng khi kế thừa `AbstractController` trong tệp `./src/Eccube/Controller/AbstractController.php`.

#### Sử dụng Dịch vụ không có trong AbstractController

Bằng cách sử dụng Injection, bạn cũng có thể sử dụng các dịch vụ không có trong `AbstractController`. Ví dụ dưới đây, lấy tên cửa hàng từ `BaseInfo`.

```php
<?php

namespace Customize\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SamplePageController
{
    /** @var \Eccube\Entity\BaseInfo */
    protected $BaseInfo;

    /**
     * SamplePageController constructor.
     * @param \Eccube\Repository\BaseInfoRepository $baseInfoRepository
     */
    public function __construct(\Eccube\Repository\BaseInfoRepository $baseInfoRepository)
    {
        $this->BaseInfo = $baseInfoRepository->get();
    }

    /**
     * @Method("GET")
     * @Route("/sample")
     */
    public function testMethod()
    {
        return new Response('Shop name is '.$this->BaseInfo->getShopName());
    }
}
```

### Tạo Controller không cần hiển thị giao diện

Đối với các controller không cần hiển thị giao diện, bạn phải luôn trả về một đối tượng Response.  
(Không được kết thúc quá trình bằng `exit()`, vì điều này sẽ làm EC-CUBE không hoạt động bình thường).

Bạn cũng có thể chỉ định mã phản hồi và tiêu đề cho Response.

```php
    /**
     * @Method("GET")
     * @Route("/sample")
     */
    public function testMethod()
    {
        return new Response(
          '',
          Response::HTTP_OK,
          array('Content-Type' => 'text/plain; charset=utf-8')
        );
    }
```

```
$ curl -D - http://SiteURL/sample
HTTP/1.1 200 OK
Content-Type: text/plain; charset=utf-8
```

## Thông tin tham khảo

EC-CUBE 4 sử dụng cơ chế Controller của Symfony.  
Để tìm hiểu thêm về các phương pháp tùy chỉnh khác, hãy tham khảo tài liệu của Symfony.

[Controller](https://symfony.com/doc/current/controller.html){:target="_blank"}