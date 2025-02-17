---
title: Tùy chỉnh Repository
keywords: core カスタマイズ リポジトリ
tags: [core, repository]
permalink: customize_repository
folder: customize
---

## Mở rộng QueryBuilder [#2285](https://github.com/EC-CUBE/ec-cube/pull/2285){:target="_blank"}, [#2298](https://github.com/EC-CUBE/ec-cube/pull/2298){:target="_blank"}

Bạn có thể tùy chỉnh thứ tự sắp xếp và điều kiện tìm kiếm đối với các phương thức tạo `QueryBuilder` trong các lớp repository. Các phương thức sau có thể sử dụng:

| Lớp Repository                                             | QueryKey                               |
|-----------------------------------------------------------|----------------------------------------|
| ProductRepository::getQueryBuilderBySearchData()          | QueryKey::PRODUCT_SEARCH              |
| ProductRepository::getQueryBuilderBySearchDataForAdmin()  | QueryKey::PRODUCT_SEARCH_ADMIN        |
| ProductRepository::getFavoriteProductQueryBuilderByCustomer() | QueryKey::PRODUCT_GET_FAVORITE    |
| CustomerRepository::getQueryBuilderBySearchData()         | QueryKey::CUSTOMER_SEARCH             |
| OrderRepository::getQueryBuilderBySearchData()            | QueryKey::ORDER_SEARCH                |
| OrderRepository::getQueryBuilderBySearchDataForAdmin()    | QueryKey::ORDER_SEARCH_ADMIN          |
| OrderRepository::getQueryBuilderByCustomer()              | QueryKey::ORDER_SEARCH_BY_CUSTOMER    |
| LoginHistoryRepository::getQueryBuilderBySearchDataForAdmin() | QueryKey::LOGIN_HISTORY_SEARCH_ADMIN |

※ `QueryKey::LOGIN_HISTORY_SEARCH_ADMIN` chỉ có từ EC-CUBE 4.1 trở đi.

Dưới đây là các interface được cung cấp để tùy chỉnh:

| Interface/Lớp          | Mô tả                             |
|------------------------|----------------------------------|
| QueryCustomizer        | Tự do thay đổi QueryBuilder      |
| OrderByCustomizer      | Thay đổi thứ tự sắp xếp          |
| WhereCustomizer        | Thêm điều kiện tìm kiếm          |
| JoinCustomizer         | Thêm bảng kết hợp                |

### Ví dụ triển khai

Dưới đây là ví dụ luôn sắp xếp danh sách sản phẩm theo ID trong `ProductRepository::getQueryBuilderBySearchDataForAdmin()`.  
Khi chỉ định phương thức cần áp dụng trong `getQueryKey()`, tùy chỉnh sẽ tự động có hiệu lực.

```php
<?php

namespace Customize\Repository;

use Eccube\Doctrine\Query\OrderByClause;
use Eccube\Doctrine\Query\OrderByCustomizer;
use Eccube\Repository\QueryKey;

class AdminProductListCustomizer extends OrderByCustomizer
{
    /**
     * Luôn sắp xếp theo ID sản phẩm.
     *
     * @param array $params
     * @param $queryKey
     * @return OrderByClause[]
     */
    protected function createStatements($params, $queryKey)
    {
        return [new OrderByClause('p.id')];
    }

    /**
     * Áp dụng cho ProductRepository::getQueryBuilderBySearchDataForAdmin.
     *
     * @return string
     * @see \Eccube\Repository\ProductRepository::getQueryBuilderBySearchDataForAdmin()
     * @see QueryKey
     */
    public function getQueryKey()
    {
        return QueryKey::PRODUCT_SEARCH_ADMIN;
    }
}
```