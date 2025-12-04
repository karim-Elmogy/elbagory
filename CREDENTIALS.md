# بيانات الدخول

## لوحة التحكم (Admin Panel)

### بيانات الدخول الافتراضية:

**البريد الإلكتروني:** `admin@example.com`  
**كلمة المرور:** `password`

### رابط لوحة التحكم:
```
http://localhost:8000/admin/dashboard
```

أو
```
http://elbagory.test/admin/dashboard
```

---

## إنشاء مستخدم جديد للوحة التحكم

يمكنك إنشاء مستخدم جديد من خلال Tinker:

```bash
php artisan tinker
```

ثم في Tinker:

```php
$adminRole = App\Models\Role::where('slug', 'admin')->first();

App\Models\User::create([
    'name' => 'اسم المستخدم',
    'email' => 'email@example.com',
    'password' => bcrypt('كلمة_المرور'),
    'role_id' => $adminRole->id,
]);
```

---

## الأدوار المتاحة:

1. **مدير النظام** (admin) - جميع الصلاحيات
2. **محاسب** (accountant) - إدارة الفواتير والمدفوعات
3. **مسؤول مخزون** (inventory_manager) - إدارة المنتجات والمخزون
4. **كاشير** (cashier) - إدارة الطلبات والمبيعات

---

## ملاحظات:

- تأكد من تشغيل `php artisan db:seed` لإنشاء المستخدم الافتراضي
- يمكنك تغيير كلمة المرور من لوحة التحكم بعد الدخول
- للحصول على صلاحيات المدير، يجب أن يكون للمستخدم `role_id` يشير إلى دور `admin`


