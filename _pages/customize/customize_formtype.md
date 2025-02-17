---
title: Tùy chỉnh FormType
keywords: core カスタマイズ FormType
tags: [core, formtype]
permalink: customize_formtype
folder: customize
---

## Mở rộng Form bằng FormExtension

Cơ chế `FormExtension` cho phép bạn tùy chỉnh các biểu mẫu (form) hiện có trong EC-CUBE.

### Cách mở rộng

Bạn có thể tạo một tệp lớp kế thừa `AbstractTypeExtension` trong thư mục `./app/Customize/Form/Extension/`.  
Sau khi tạo, EC-CUBE sẽ tự động nhận diện nó là một `FormExtension`.

#### Chỉ định loại biểu mẫu cần mở rộng

- **EC-CUBE 4.0:** Bạn cần triển khai phương thức `getExtendedType()` để chỉ định loại biểu mẫu sẽ mở rộng.

```php
public function getExtendedType()
{
    return EntryType::class;
}
```

- **EC-CUBE 4.1 trở lên:** Bạn cần triển khai phương thức `getExtendedTypes()` để chỉ định loại biểu mẫu.

```php
public static function getExtendedTypes(): iterable
{
    yield EntryType::class;
}
```

#### Các phương thức mở rộng

Bạn có thể ghi đè các phương thức sau để thay đổi tham số truyền vào và tùy chỉnh biểu mẫu:

- `buildForm()`
- `buildView()`
- `configureOptions()`
- `finishView()`

EC-CUBE 4 sử dụng cơ chế `FormExtension` của Symfony.  
Chi tiết cách mở rộng, tham khảo tài liệu Symfony:  
[Symfony Form Type Extension](https://symfony.com/doc/current/form/create_form_type_extension.html)

---

### Ví dụ

Dưới đây là ví dụ mở rộng biểu mẫu trên trang đăng ký thành viên, làm cho trường "Tên công ty" (`company_name`) trở thành bắt buộc.

📂 **Tạo tệp sau:**  
`./app/Customize/Form/Extension/CompanyNameRequiredExtension.php`

```php
<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Front\EntryType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyNameRequiredExtension extends AbstractTypeExtension
{
    /**
     * Thêm yêu cầu bắt buộc cho trường company_name.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $builder->get('company_name')->getOptions();

        $options['required'] = true;
        $options['constraints'] = [ new NotBlank() ];
        $options['attr']['placeholder'] = 'Tên công ty';

        $builder->add('company_name', TextType::class, $options);
    }

    /**
     * Định nghĩa loại biểu mẫu được mở rộng (EC-CUBE 4.0).
     */
    public function getExtendedType()
    {
        return EntryType::class;
    }
    
    /**
     * Định nghĩa loại biểu mẫu được mở rộng (EC-CUBE 4.1 trở lên).
     */
    public static function getExtendedTypes(): iterable
    {
        yield EntryType::class;
    }
}
```

---

## Mở rộng biểu mẫu từ Entity

Xem thêm tại: [Tùy chỉnh Entity](/customize_entity).
