---
title: Tùy chỉnh Entity
keywords: core カスタマイズ Entity
tags: [core, entity]
permalink: customize_entity
folder: customize
---

## Mở rộng Entity [#2267](https://github.com/EC-CUBE/ec-cube/pull/2267){:target="_blank"}

### Cách mở rộng cơ bản

Bằng cách sử dụng trait và chú thích `@EntityExtension`, bạn có thể mở rộng các trường của Entity. Vì Proxy class được tạo mà không sử dụng kế thừa, nên có thể đồng thời mở rộng từ nhiều plugin hoặc tùy chỉnh riêng.

```php
<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
  * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $maker_name;
}
```

Trong tham số của chú thích `@EntityExtension`, bạn chỉ định lớp mà bạn muốn áp dụng trait. Trong trait, bạn triển khai các trường muốn thêm vào. Sử dụng các chú thích của Doctrine ORM như `@ORM\Column` để thiết lập định nghĩa cơ sở dữ liệu.

Sau khi triển khai trait, bạn có thể tạo Proxy class bằng cách sử dụng lệnh `bin/console eccube:generate:proxies`.

```
bin/console eccube:generate:proxies
```

Sau khi tạo Proxy, bạn cần phản ánh định nghĩa vào cơ sở dữ liệu bằng lệnh `bin/console doctrine:schema:update`.

```
## Xóa cache để chắc chắn Proxy class được nhận diện
bin/console cache:clear --no-warmup

## Kiểm tra SQL sẽ được thực thi
bin/console doctrine:schema:update --dump-sql

## Thực thi SQL
bin/console doctrine:schema:update --dump-sql --force
```

#### Sử dụng trong Controller và Twig

Khi sử dụng trong Controller hoặc Twig, bạn có thể sử dụng mà không cần phải đặc biệt lưu ý gì.

```php
// Ví dụ sử dụng trong Controller
public function index()
{
    $Product = $this->productRepository->find(1);
    dump($Product->maker_name);

    $Product->maker_name = 'あああ';
    $this->entityManger->persist($Product);
    $this->entityManger->flush();
    ...
```

```twig
{# Ví dụ sử dụng trong Twig #}
{{ Product.maker_name }}
```

### Tự động tạo form từ Entity

Bằng cách thêm chú thích `@FormAppend` vào trường mở rộng bởi chú thích `@EntityExtension`, bạn có thể tự động tạo form.

```php
<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Eccube\EntityExtension("Eccube\Entity\BaseInfo")
 */
trait BaseInfoTrait
{
    /**
     * @ORM\Column(name="company_name_vn", type="string", length=255, nullable=true)
     * @Eccube\FormAppend
     * @Assert\NotBlank(message="にゅうりょくしてくださいね！！！")
     */
    public $company_name_vn;
}
```

Khi thêm chú thích `@FormAppend`, form sẽ tự động thêm trường mới vào form đang sử dụng entity đó. Nếu bạn muốn kiểm tra dữ liệu đầu vào, bạn có thể sử dụng các chú thích chuẩn của Symfony như `@NotBlank`.

Nếu bạn muốn tùy chỉnh form chi tiết hơn, có thể sử dụng `auto_render=true` và chỉ định các tham số như `form_theme`, `type`, `option` cho từng trường riêng biệt.

```php
<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Eccube\EntityExtension("Eccube\Entity\BaseInfo")
 */
trait BaseInfoTrait
{
    /**
     * @ORM\Column(name="company_name_vn", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="入力してください")
     * @Eccube\FormAppend(
     *     auto_render=true,
     *     form_theme="Form/company_name_vn.twig",
     *     type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *     options={
     *          "required": true,
     *          "label": "会社名(VN)"
     *     })
     */
    public $company_name_vn;
}
```