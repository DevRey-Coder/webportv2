# POS API example application

## Testing API

### API Reference

#### Register (Post)

```http
  https://d.mmsdev.site/api/v1/register
```

| Arguments               | Type     | Description                    |
| :---------------------- | :------- | :----------------------------- |
| `name`                  | `string` | **Required / min:3** example           |
| `email`                 | `string` | **Required / unique** example@gmail.com |
| `password`              | `string` | **Required / min:8** asdffdsa          |
| `address` | `string` | **Required / max:30**           |
| `gender` | `string` | **Required**           |
| `date_of_birth` | `string` | **Required 10/20/19**           |

### Login(POST)

```http
  https://d.mmsdev.site/api/v1/login
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required** lex@gmail.com |
| `password` | `string` | **Required** asdffdsa |

### Logout (POST)

```http
   https://d.mmsdev.site/api/v1/logout
```

### LogoutAll (POST)

```http
   https://d.mmsdev.site/api/v1/logout-all
```

### LogoutAll (POST)

```http
   https://d.mmsdev.site/api/v1/logoutAll
```

# User
=====
### Get All Users (GET)
```
https://d.mmsdev.site/api/v1/users
```

### Get Current User (GET)
```
https://d.mmsdev.site/api/v1/user-current
```

### Ban User (Post)
```
https://d.mmsdev.site/api/v1/user-ban/13
```

### UnBan User (Delete)
```
https://d.mmsdev.site/api/v1/user-unban/13
```

# Product
=======
   https://d.mmsdev.site/api/v1/logout-all
```

## Product

### Get Products (GET)

```http
   https://d.mmsdev.site/api/v1/product
```

### Get Single Product (Get)

```http
  https://d.mmsdev.site/api/v1/product/{id}
```

### Create Product (POST)

```http
  https://d.mmsdev.site/api/v1/product
```

| Arguments          | Type      | Description             |
| :----------------- | :-------- | :---------------------- |
| `name`             | `string`  | **Required** Rice Toner |
| `actual_price`     | `integer` | **Required** 50000      |
| `sale_price`       | `integer` | **Required** 55000      |
| `total_price`      | `integer` | **Required** 55000      |
| `unit`             | `integer` | **Required** 1          |
| `more_information` | `string`  | **Required** bar nyar   |
| `photo`            | `string`  | **Required** jpg        |

### Update Product (PUT)

```http
  https://d.mmsdev.site/api/v1/product/{id}
```

#### You can update with only singe Parameter or more

| Arguments          | Type      | Description             |
| :----------------- | :-------- | :---------------------- |
| `name`             | `string`  | **Required** Rice Toner |
| `actual_price`     | `integer` | **Required** 50000      |
| `sale_price`       | `integer` | **Required** 55000      |
| `total_price`      | `integer` | **Required** 55000      |
| `unit`             | `integer` | **Required** 1          |
| `more_information` | `string`  | **Required** bar nyar   |
| `photo`            | `string`  | **Required** jpg        |

### Delete Product (DELETE)

```http
  https://d.mmsdev.site/api/v1/product/{id}
```

# Brand

### Get Brands (GET)

```http
   https://d.mmsdev.site/api/v1/brand
```

### Get Single Brand (Get)

```http
  https://d.mmsdev.site/api/v1/brand/{id}
```

### Create Brand (POST)

```http
  https://d.mmsdev.site/api/v1/brand
```

| Arguments     | Type     | Description           |
| :------------ | :------- | :-------------------- |
| `name`        | `string` | **Required** Lexus    |
| `company`     | `string` | **Required** TOYOTA   |
| `information` | `text`   | **Required** bar nyar |
| `photo`       | `string` | **Required** jpg      |

### Update Brand (PUT)

```http
  https://d.mmsdev.site/api/v1/brand/{id}
```

#### You can update with only singe Parameter or more

| Arguments     | Type     | Description           |
| :------------ | :------- | :-------------------- |
| `name`        | `string` | **Required** Lexus    |
| `company`     | `string` | **Required** TOYOTA   |
| `information` | `text`   | **Required** bar nyar |
| `photo`       | `string` | **Required** jpg      |

### Delete Brand (DELETE)

```http
  https://d.mmsdev.site/api/v1/brand/{id}
```

# Stock

### Get Stocks (GET)

```http
   https://d.mmsdev.site/api/v1/stock
```

### Get Single Stock (Get)

```http
  https://d.mmsdev.site/api/v1/stock/{id}
```

### Create Stock (POST)

```http
  https://d.mmsdev.site/api/v1/stock
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `user_id` | `integer` | **Required** 6 |
| `product_id` | `integer` | **Required** 5 |
| `quantity` | `integer` | **Required** 4 |
| `more` | `text` | **Required** bar nyar |

### Update Stock (PUT)

```http
  https://d.mmsdev.site/api/v1/stock/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `user_id` | `integer` | **Required** 6 |
| `product_id` | `integer` | **Required** 5 |
| `quantity` | `integer` | **Required** 4 |
| `more` | `text` | **Required** bar nyar |

### Delete Stock (DELETE)

```http
  https://d.mmsdev.site/api/v1/stock/{id}
```

# Voucher

### Get Vouchers (GET)

```http
   https://d.mmsdev.site/api/v1/voucher
```

### Get Single Voucher (Get)

```http
  https://d.mmsdev.site/api/v1/voucher/{id}
```

### Create Voucher (POST)

```http
  https://d.mmsdev.site/api/v1/voucher
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `customer` | `string` | **Required** Lextor |
| `phone` | `integer` | **Nullable** 09199199199 |
| `voucher_number` | `string` | **Required** 7777L34 |
| `total` | `integer` | **Required** 40000 |
| `tax` | `integer` | **Required** 40 |
| `net_total` | `integer` | **Required** 4000 |

### Update Voucher (PUT)

```http
  https://d.mmsdev.site/api/v1/voucher/{id}
```

#### You can update with only singe Parameter or more

| Arguments        | Type      | Description              |
| :--------------- | :-------- | :----------------------- |
| `customer`       | `string`  | **Required** Lextor      |
| `phone`          | `integer` | **Nullable** 09199199199 |
| `voucher_number` | `string`  | **Required** 77777       |
| `total`          | `integer` | **Required** 40000       |
| `tax`            | `integer` | **Required** 40          |
| `net_total`      | `integer` | **Required** 4000        |

### Delete Voucher (DELETE)

```http
  https://d.mmsdev.site/api/v1/voucher/{id}
```

# Voucher-record
### Get Voucher-records (GET)

```http
  https://d.mmsdev.site/api/v1/voucher-record
```

### Get Single Voucher-record (Get)

```http
 https://d.mmsdev.site/api/v1/voucher-record/{id}
```

### Create Voucher-record (POST)

```http
  https://d.mmsdev.site/api/v1/voucher-record
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `voucher_id` | `integer` | **Required** 12345 |
| `product_id` | `integer` | **Required** 2233 |
| `quantity` | `integer` | **Required** 20 |
| `cost` | `integer` | **Required** 2000 |

### Update Voucher-record (PUT)

```http
  https://d.mmsdev.site/api/v1/voucher-record/{id}
```

  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `voucher_id` | `integer` | **Required** 12345 |
| `product_id` | `integer` | **Required** 2233 |
| `quantity` | `integer` | **Required** 20 |
| `cost` | `integer` | **Required** 2000 |

### Delete Voucher-record (DELETE)

```http
  https://mmsdev.site/api/v1/voucher-record/{id}
```

# Photo

### Get Photo (GET)

```http
   https://d.mmsdev.site/api/v1/photo
```
### Get Single photo (Get)

```http
  https://d.mmsdev.site/api/v1/photo/{id}
```
### Create Photo (POST)
=======
  https://d.mmsdev.site/api/v1/voucher-record/{id}
```

## Media

### Get Media (GET)

```http
  https://d.mmsdev.site/api/v1/photo
```

### Get Single Media (Get)

```http
 https://d.mmsdev.site/api/v1/photo/{id}
```

### Create Media (POST)

```http
  https://d.mmsdev.site/api/v1/photo
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `photo[]` | `file` | **Required** cat-551554_640.jpg|

### Delete Photo (DELETE)

```http
  https://mmsdev.site/api/v1/photo/{id}
```
# Sale

#
### Selling Products (Post)
```
https://mmsdev.site/api/v1/check-out
```

## raw (Json)

```
==Json==
{
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 2,
            "quantity": 1
        }
    ]
}
=======
| Arguments | Type           | Description  |
| :-------- | :------------- | :----------- |
| `photo[]` | `png,jpg,jpeg` | **Required** |

### Delete Media (DELETE)

```http
  https://d.mmsdev.site/api/v1/photo/{id}
```
