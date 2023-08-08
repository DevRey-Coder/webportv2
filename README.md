# POS API example application
## Testing API
### API Reference
#### Register (Post)

```http
  https://pos-app.mms-it.com/api/v1/register
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** example |
| `email` | `string` | **Required** example@gmail.com |
| `password` | `string` | **Required** asdffdsa |
| `password_confirmation` | `string` | **Required** asdffdsa |

### Login(POST)

```http
  https://pos-app.mms-it.com/api/v1/login
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required** aht@gmail.com |
| `password` | `string` | **Required** asdffdsa |

### Logout (POST)

```http
   https://pos-app.mms-it.com/api/v1/logout
```
### LogoutAll (POST)

```http
   https://pos-app.mms-it.com/api/v1/logoutAll
```
## Product
### Get Products (GET)

```http
   https://pos-app.mms-it.com/api/v1/product
```
### Get Single Product (Get)

```http
  https://pos-app.mms-it.com/api/v1/product/{id}
```
### Create Product (POST)

```http
  https://pos-app.mms-it.com/api/v1/product
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** Rice Toner |
| `actual_price` | `integer` | **Required** 50000 |
| `sale_price` | `integer` | **Required** 55000 |
| `total_price` | `integer` | **Required** 55000 |
| `unit` | `integer` | **Required** 1 |
| `more_information` | `string` | **Required** bar nyar |
| `photo` | `string` | **Required** jpg |

### Update Product (PUT)

```http
  https://pos-app.mms-it.com/api/v1/product/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** Rice Toner |
| `actual_price` | `integer` | **Required** 50000 |
| `sale_price` | `integer` | **Required** 55000 |
| `total_price` | `integer` | **Required** 55000 |
| `unit` | `integer` | **Required** 1 |
| `more_information` | `string` | **Required** bar nyar |
| `photo` | `string` | **Required** jpg |

### Delete Product (DELETE)

```http
  https://pos-app.mms-it.com/api/v1/product/{id}
```

## Brand
### Get Brands (GET)

```http
   https://pos-app.mms-it.com/api/v1/brand
```
### Get Single Brand (Get)

```http
  https://pos-app.mms-it.com/api/v1/brand/{id}
```
### Create Brand (POST)

```http
  https://pos-app.mms-it.com/api/v1/brand
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** Lexus |
| `company` | `string` | **Required** TOYOTA |
| `information` | `text` | **Required** bar nyar |
| `photo` | `string` | **Required** jpg |


### Update Brand (PUT)

```http
  https://pos-app.mms-it.com/api/v1/Brand/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** Lexus |
| `company` | `string` | **Required** TOYOTA |
| `information` | `text` | **Required** bar nyar |
| `photo` | `string` | **Required** jpg |

### Delete Brand (DELETE)

```http
  https://pos-app.mms-it.com/api/v1/brand/{id}
```

## Stock
### Get Stocks (GET)

```http
   https://pos-app.mms-it.com/api/v1/stock
```
### Get Single Stock (Get)

```http
  https://pos-app.mms-it.com/api/v1/stock/{id}
```
### Create Stock (POST)

```http
  https://pos-app.mms-it.com/api/v1/stock
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `quantity` | `integer` | **Required** 4 |
| `more` | `text` | **Required** bar nyar |

### Update Stock (PUT)

```http
  https://pos-app.mms-it.com/api/v1/stock/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `quantity` | `integer` | **Required** 4 |
| `more` | `text` | **Required** bar nyar |

### Delete Stock (DELETE)

```http
  https://pos-app.mms-it.com/api/v1/stock/{id}
```

## Voucher
### Get Vouchers (GET)

```http
   https://pos-app.mms-it.com/api/v1/voucher
```
### Get Single Voucher (Get)

```http
  https://pos-app.mms-it.com/api/v1/voucher/{id}
```
### Create Voucher (POST)

```http
  https://pos-app.mms-it.com/api/v1/voucher
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `customer` | `string` | **Required** Lextor |
| `phone` | `integer` | **Nullable** 09199199199 |
| `voucher_number` | `string` | **Required** 77777 |
| `total` | `integer` | **Required** 40000 |
| `tax` | `integer` | **Required** 40 |
| `net_total` | `integer` | **Required** 4000 |


### Update Voucher (PUT)

```http
  https://pos-app.mms-it.com/api/v1/voucher/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `customer` | `string` | **Required** Lextor |
| `phone` | `integer` | **Nullable** 09199199199 |
| `voucher_number` | `string` | **Required** 77777 |
| `total` | `integer` | **Required** 40000 |
| `tax` | `integer` | **Required** 40 |
| `net_total` | `integer` | **Required** 4000 |

### Delete Voucher (DELETE)

```http
  https://pos-app.mms-it.com/api/v1/voucher/{id}
```

## Voucher-record
### Get Voucher-records (GET)

```http
   https://pos-app.mms-it.com/api/v1/voucher-record
```
### Get Single Voucher-record (Get)

```http
  https://pos-app.mms-it.com/api/v1/voucher-record/{id}
```
### Create Voucher-record (POST)

```http
  https://pos-app.mms-it.com/api/v1/voucher-record
```

| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `quantity` | `integer` | **Required** 20 |
| `cost` | `integer` | **Required** 2000 |

### Update Voucher-record (PUT)

```http
  https://pos-app.mms-it.com/api/v1/voucher-record/{id}
```
  #### You can update with only singe Parameter or more
| Arguments | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `quantity` | `integer` | **Required** 20 |
| `cost` | `integer` | **Required** 2000 |

### Delete Voucher-record (DELETE)

```http
  https://pos-app.mms-it.com/api/v1/voucher-record/{id}
```


