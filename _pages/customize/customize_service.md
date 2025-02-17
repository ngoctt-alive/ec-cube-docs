---
title: Tùy chỉnh quy trình mua hàng
keywords: core カスタマイズ PurchaseFlow Cart Shopping
tags: [core, service, purchaseflow]
permalink: customize_service
folder: customize
---

## Tùy chỉnh giỏ hàng [#2613](https://github.com/EC-CUBE/ec-cube/issues/2613){:target="_blank"}  

Bằng cách triển khai các lớp `CartItemComparator` và `CartItemAllocator`, bạn có thể tùy chỉnh hành vi khi thêm sản phẩm vào giỏ hàng.  

![Sơ đồ trình tự](./images/spec/order_progress.png)  

### Chia tách thành các chi tiết riêng ngay cả khi sản phẩm và quy cách giống nhau  

Trong triển khai tiêu chuẩn, chi tiết giỏ hàng sẽ được chia tách theo từng quy cách sản phẩm.  
Ví dụ, khi bạn tùy chỉnh để thêm tùy chọn sản phẩm như gói quà, bằng cách triển khai `CartItemComparator`, bạn có thể chia tách chi tiết ngay cả khi sản phẩm và quy cách giống nhau nhưng có hoặc không có gói quà.  

```php
<?php
namespace Eccube\Service\Cart;

use Eccube\Entity\CartItem;

/**
 * CartItemComparator so sánh theo quy cách sản phẩm và tùy chọn sản phẩm
 */
class ProductClassAndOptionComparator implements CartItemComparator
{
    /**
     * @param CartItem $Item1 Chi tiết 1
     * @param CartItem $Item2 Chi tiết 2
     * @return boolean Trả về true nếu chúng là cùng một chi tiết
     */
    public function compare(CartItem $Item1, CartItem $Item2)
    {
        $ProductClass1 = $Item1->getProductClass();
        $ProductClass2 = $Item2->getProductClass();
        $product_class_id1 = $ProductClass1 ? (string) $ProductClass1->getId() : null;
        $product_class_id2 = $ProductClass2 ? (string) $ProductClass2->getId() : null;

        if ($product_class_id1 === $product_class_id2) {
            // So sánh thêm ProductOption
            return $Item1->getProductOption()->getId() === $Item2->getProductOption()->getId();
        }
        return false;
    }
}
```

Để kích hoạt `CartItemComparator`, hãy tạo `app/config/eccube/packages/cart.yaml` và thêm định nghĩa của `CartItemComparator`.  

```yaml
services:
    Eccube\Service\Cart\CartItemComparator:
      class: Eccube\Service\Cart\ProductClassAndOptionComparator
```  

### Cho phép thêm vào giỏ hàng các sản phẩm có phương thức thanh toán khác nhau  

Ví dụ, khi có phương thức vận chuyển A/B và sản phẩm A/B:  

+ **Phương thức vận chuyển**  
    + Vận chuyển A: Loại bán hàng A / Thẻ tín dụng  
    + Vận chuyển B: Loại bán hàng A / Thanh toán khi nhận hàng  
+ **Sản phẩm**  
    + Sản phẩm A: Loại bán hàng A  
    + Sản phẩm B: Loại bán hàng B  

Trong EC-CUBE 3.0, nếu bạn thêm sản phẩm A vào giỏ, sau đó thêm sản phẩm B, sẽ xuất hiện lỗi: "Sản phẩm này không thể mua cùng lúc."  

Bằng cách triển khai `CartItemAllocator`, bạn có thể tách giỏ hàng dựa trên tiêu chí tùy chọn.  
Ví dụ, với sản phẩm đặt trước, bạn có thể **thêm vào cùng một giỏ hàng nhưng tách thành đơn hàng riêng biệt**.  

```php
<?php
namespace Eccube\Service\Cart;

use Eccube\Entity\CartItem;

/**
 * CartItemAllocator phân loại giỏ hàng theo loại bán hàng và cờ đặt trước
 */
class SaleTypeAndReserveCartAllocator implements CartItemAllocator
{
    /**
     * Xác định giỏ hàng đích dựa trên sản phẩm
     *
     * @param CartItem $Item Sản phẩm trong giỏ hàng
     * @return string
     */
    public function allocate(CartItem $Item)
    {
        $ProductClass = $Item->getProductClass();
        if ($ProductClass && $ProductClass->getSaleType()) {
            $salesTypeId = (string) $ProductClass->getSaleType()->getId();
            // isReserveItem cần được tùy chỉnh thêm
            if ($ProductClass->isReserveItem()) {
                return $salesTypeId.':R' ;
            }
            return $salesTypeId;
        }
        throw new \InvalidArgumentException('ProductClass/SaleType not found');
    }
}
```

Để kích hoạt `CartItemAllocator`, hãy tạo `app/config/eccube/packages/cart.yaml` và thêm định nghĩa của `CartItemAllocator`.  

```yaml
services:
    Eccube\Service\Cart\CartItemAllocator:
      class: Eccube\Service\Cart\SaleTypeAndReserveCartAllocator
```  


### Xác thực số lượng tối đa của từng giỏ hàng  

Giới hạn số lượng sản phẩm trong giỏ hàng có thể được thay đổi bằng cách ghi đè `CartItemLimitPolicy`.  

Ví dụ: Giả sử bạn muốn áp dụng các giới hạn số lượng khác nhau cho từng loại sản phẩm, bạn có thể triển khai `CartItemLimitPolicy` tùy chỉnh.  

```php
<?php
namespace Eccube\Service\Cart;

use Eccube\Entity\CartItem;
use Eccube\Service\Cart\CartItemLimitPolicy;

class SaleTypeCartItemLimitPolicy implements CartItemLimitPolicy
{
    /**
     * Kiểm tra số lượng tối đa của giỏ hàng
     *
     * @param CartItem[] $CartItems Danh sách sản phẩm trong giỏ hàng
     * @param CartItem $NewItem Sản phẩm đang thêm vào
     * @param int $quantity Số lượng thêm vào
     * @return boolean Trả về true nếu có thể thêm vào giỏ
     */
    public function validate(array $CartItems, CartItem $NewItem, $quantity)
    {
        $ProductClass = $NewItem->getProductClass();
        if (!$ProductClass) {
            return false;
        }

        $saleTypeId = $ProductClass->getSaleType()->getId();
        $maxLimit = ($saleTypeId == 1) ? 5 : 10; // Ví dụ: loại bán hàng 1 có giới hạn 5, các loại khác là 10.

        $totalQuantity = $quantity;
        foreach ($CartItems as $CartItem) {
            if ($CartItem->getProductClass()->getSaleType()->getId() == $saleTypeId) {
                $totalQuantity += $CartItem->getQuantity();
            }
        }

        return $totalQuantity <= $maxLimit;
    }
}
```

Để kích hoạt `CartItemLimitPolicy`, hãy tạo `app/config/eccube/packages/cart.yaml` và thêm định nghĩa của `CartItemLimitPolicy`.  

```yaml
services:
    Eccube\Service\Cart\CartItemLimitPolicy:
      class: Eccube\Service\Cart\SaleTypeCartItemLimitPolicy
```  

### Lưu giỏ hàng vào cơ sở dữ liệu  

Trong EC-CUBE tiêu chuẩn, giỏ hàng được lưu trong phiên (`session`). Nếu muốn lưu giỏ hàng vào cơ sở dữ liệu để có thể duy trì giữa các lần đăng nhập, bạn có thể tùy chỉnh bằng cách thay đổi `CartProcessor`.  

Dưới đây là ví dụ về `CartProcessor` ghi dữ liệu vào cơ sở dữ liệu:  

```php
<?php
namespace Eccube\Service\Cart;

use Eccube\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseCartProcessor implements CartProcessor
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveCart(array $CartItems, $Customer)
    {
        foreach ($CartItems as $CartItem) {
            $CartItem->setCustomer($Customer);
            $this->entityManager->persist($CartItem);
        }
        $this->entityManager->flush();
    }

    public function loadCart($Customer)
    {
        return $this->entityManager->getRepository(CartItem::class)->findBy(['Customer' => $Customer]);
    }
}
```

Để kích hoạt `CartProcessor`, hãy tạo `app/config/eccube/packages/cart.yaml` và thêm định nghĩa của `CartProcessor`.  

```yaml
services:
    Eccube\Service\Cart\CartProcessor:
      class: Eccube\Service\Cart\DatabaseCartProcessor
```  