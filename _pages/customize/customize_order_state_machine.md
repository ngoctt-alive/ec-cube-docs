---
title: Tùy chỉnh trạng thái đơn hàng
keywords: core カスタマイズ 受注ステータス OrderStatus
tags: [core, OrderStatus]
permalink: customize_order_state_machine
folder: customize
---

## Mở rộng trạng thái đơn hàng [#3325](https://github.com/EC-CUBE/ec-cube/pull/3325){:target="_blank"}  

![Sơ đồ trạng thái đơn hàng](./images/spec/order-statemachine.png)  

Vui lòng kiểm tra thêm [Luồng xử lý đơn hàng](/spec_order).  

### Cách mở rộng cơ bản  

Việc triển khai sử dụng [Symfony Workflow Component](https://symfony.com/doc/current/components/workflow.html).  

Để thêm xử lý khi thay đổi trạng thái, cần triển khai các sự kiện liên quan đến quá trình chuyển đổi trạng thái.  
Các trạng thái và quá trình chuyển đổi được định nghĩa tại [app/config/eccube/packages/order_state_machine.php](https://github.com/EC-CUBE/ec-cube/blob/4.0/app/config/eccube/packages/order_state_machine.php).  

Bằng cách triển khai các sự kiện, bạn có thể thêm xử lý tùy chỉnh vào quá trình thay đổi trạng thái đơn hàng.  

| Trạng thái hiện tại          | Trạng thái tiếp theo | Sự kiện                                      |  
|------------------------------|----------------------|----------------------------------------------|  
| Tiếp nhận mới                | Đã thanh toán       | `workflow.order.transition.pay`             |  
| Tiếp nhận mới, Đã thanh toán | Đang xử lý          | `workflow.order.transition.packing`         |  
| Tiếp nhận mới, Đang xử lý, Đã thanh toán | Hủy | `workflow.order.transition.cancel` |  
| Hủy                         | Đang xử lý          | `workflow.order.transition.back_to_in_progress` |  
| Tiếp nhận mới, Đang xử lý, Đã thanh toán | Đã giao hàng | `workflow.order.transition.ship` |  
| Đã giao hàng                | Trả hàng           | `workflow.order.transition.return`          |  
| Trả hàng                    | Đã giao hàng       | `workflow.order.transition.cancel_return`   |  

- Ví dụ: Nếu muốn thêm xử lý khi trả hàng  
    - Triển khai `EventSubscriberInterface` để lắng nghe sự kiện `workflow.order.transition.return`.  

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Entity\Order;
use Symfony\Component\Workflow\Event\Event;

class SampleTransitionListener implements EventSubscriberInterface
{
    /**
     * Xử lý khi trả hàng.
     *
     * @param Event $event
     */
    public function onReturn(Event $event)
    {
        /* @var Order $Order */
        $Order = $event->getSubject();
        .... /* Thực hiện xử lý */
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return ['workflow.order.transition.return' => 'onReturn'];
    }
}
```

### Tham khảo  

Các sự kiện mặc định của EC-CUBE được triển khai tại [src/Eccube/Service/OrderStateMachine.php](https://github.com/EC-CUBE/ec-cube/blob/4.0/src/Eccube/Service/OrderStateMachine.php).  

[Using Events](https://symfony.com/doc/current/workflow/usage.html#using-events){:target="_blank"}