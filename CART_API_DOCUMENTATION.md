# Cart API Documentation

## نظام السلة (Cart System)

تم إضافة نظام سلة تسوق كامل يسمح للمستخدمين بإضافة منتجات متعددة قبل إتمام عملية الشراء.

## المميزات

1. ✅ إضافة منتجات متعددة للسلة
2. ✅ تحديث كمية المنتجات في السلة
3. ✅ حذف منتجات من السلة
4. ✅ حذف المنتج تلقائياً من جميع السلات عند حذفه من النظام
5. ✅ حذف المنتج تلقائياً من السلات عند نفاذ الكمية (quantity = 0)
6. ✅ التحقق من توفر المنتجات قبل الدفع
7. ✅ منع الدفع إذا كانت هناك منتجات غير متوفرة
8. ✅ إنشاء طلب من السلة مباشرة

## API Endpoints

### 1. عرض السلة (Get Cart)
```
GET /api/cart
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "items": [
      {
        "id": 1,
        "product": {
          "id": 1,
          "name": "Product Name",
          "price": 100.00,
          "quantity": 50
        },
        "quantity": 2,
        "price": 100.00,
        "subtotal": 200.00,
        "is_available": true,
        "available_quantity": 50
      }
    ],
    "total_items": 1,
    "total_price": 200.00,
    "can_checkout": true
  }
}
```

### 2. إضافة منتج للسلة (Add to Cart)
```
POST /api/cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}
```

**Response (Success):**
```json
{
  "message": "تم إضافة المنتج إلى السلة بنجاح",
  "cart": {
    "id": 1,
    "items": [...],
    "total_price": 200.00
  }
}
```

**Response (Out of Stock):**
```json
{
  "message": "الكمية المطلوبة غير متوفرة في المخزون",
  "available_quantity": 5
}
```

### 3. تحديث كمية منتج في السلة (Update Cart Item)
```
PUT /api/cart/{cartItemId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "quantity": 3
}
```

**Response:**
```json
{
  "message": "تم تحديث الكمية بنجاح",
  "cart": {
    "id": 1,
    "items": [...],
    "total_price": 300.00
  }
}
```

### 4. حذف منتج من السلة (Remove from Cart)
```
DELETE /api/cart/{cartItemId}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "تم حذف المنتج من السلة بنجاح",
  "cart": {
    "id": 1,
    "items": [...],
    "total_price": 100.00
  }
}
```

### 5. تفريغ السلة (Clear Cart)
```
DELETE /api/cart-clear
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "تم تفريغ السلة بنجاح"
}
```

### 6. التحقق من السلة قبل الدفع (Validate Cart)
```
GET /api/cart/validate
Authorization: Bearer {token}
```

**Response (Valid):**
```json
{
  "can_checkout": true,
  "message": "السلة جاهزة للدفع",
  "cart": {...}
}
```

**Response (Invalid - Out of Stock):**
```json
{
  "can_checkout": false,
  "message": "بعض المنتجات في السلة غير متوفرة",
  "unavailable_items": [
    {
      "cart_item_id": 1,
      "product_name": "Product Name",
      "requested_quantity": 10,
      "available_quantity": 5,
      "message": "الكمية المطلوبة غير متوفرة"
    }
  ]
}
```

### 7. إنشاء طلب من السلة (Create Order from Cart)
```
POST /api/orders/from-cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "shipping_address": "123 Main St, City, Country"
}
```

**Response (Success):**
```json
{
  "message": "تم إنشاء الطلب بنجاح",
  "order": {
    "id": 1,
    "slug": "ORD-1",
    "total_price": 200.00,
    "status": "pending",
    "order_items": [...]
  }
}
```

**Response (Error - Unavailable Items):**
```json
{
  "message": "بعض المنتجات في السلة غير متوفرة",
  "unavailable_items": [...]
}
```

## سلوك النظام التلقائي

### عند حذف منتج
عندما يتم حذف منتج من النظام، يتم حذفه تلقائياً من جميع السلات التي تحتوي عليه.

### عند نفاذ الكمية
عندما تصبح كمية المنتج = 0، يتم حذفه تلقائياً من جميع السلات.

### عند محاولة الدفع
- يتم التحقق من توفر جميع المنتجات
- يتم التحقق من الكميات المتوفرة
- إذا كان هناك منتج غير متوفر أو كمية غير كافية، يتم منع الدفع
- يجب على المستخدم حذف المنتجات غير المتوفرة أو تعديل الكميات

## Database Schema

### carts table
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- created_at (timestamp)
- updated_at (timestamp)
```

### cart_items table
```sql
- id (bigint, primary key)
- cart_id (bigint, foreign key -> carts.id, cascade on delete)
- product_id (bigint, foreign key -> products.id, cascade on delete)
- quantity (integer)
- price (decimal 10,2)
- created_at (timestamp)
- updated_at (timestamp)
```

## خطوات التشغيل

1. تشغيل الـ migrations:
```bash
php artisan migrate
```

2. النظام جاهز للاستخدام!

## ملاحظات مهمة

- كل مستخدم له سلة واحدة فقط
- السلة تُنشأ تلقائياً عند إضافة أول منتج
- الأسعار تُحفظ في السلة لحظة الإضافة (discounted_price أو price)
- عند إنشاء الطلب، يتم تفريغ السلة تلقائياً
- جميع العمليات محمية بـ authentication
