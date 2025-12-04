# كيفية تسجيل الدخول للوحة التحكم

## الخطوات:

### 1. تأكد من تشغيل Migrations و Seeders:

```bash
php artisan migrate
php artisan db:seed
```

أو لإعادة إنشاء قاعدة البيانات بالكامل:

```bash
php artisan migrate:fresh --seed
```

### 2. بيانات الدخول:

**البريد الإلكتروني:** `admin@example.com`  
**كلمة المرور:** `password`

### 3. خطوات تسجيل الدخول:

1. افتح المتصفح واذهب إلى:
   ```
   http://elbagory.test/login
   ```
   أو
   ```
   http://localhost:8000/login
   ```

2. أدخل البيانات:
   - **البريد الإلكتروني:** `admin@example.com`
   - **كلمة المرور:** `password`

3. اضغط على "تسجيل الدخول"

4. بعد تسجيل الدخول، ستظهر لك رابط "لوحة التحكم" في القائمة العلوية

5. أو اذهب مباشرة إلى:
   ```
   http://elbagory.test/admin/dashboard
   ```

### 4. إذا لم يعمل:

#### تحقق من وجود المستخدم:
```bash
php artisan tinker
```

ثم في Tinker:
```php
$user = App\Models\User::where('email', 'admin@example.com')->first();
if($user) {
    echo "User exists\n";
    echo "Name: " . $user->name . "\n";
    echo "Role ID: " . $user->role_id . "\n";
    if($user->role) {
        echo "Role: " . $user->role->name . " (" . $user->role->slug . ")\n";
    } else {
        echo "No role assigned!\n";
    }
} else {
    echo "User not found - run: php artisan db:seed\n";
}
```

#### إنشاء مستخدم جديد يدوياً:
```bash
php artisan tinker
```

```php
$adminRole = App\Models\Role::where('slug', 'admin')->first();

if(!$adminRole) {
    echo "Admin role not found. Run: php artisan db:seed\n";
} else {
    $user = App\Models\User::create([
        'name' => 'مدير النظام',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role_id' => $adminRole->id,
    ]);
    echo "User created successfully!\n";
}
```

### 5. ملاحظات مهمة:

- تأكد من أن المستخدم لديه `role_id` يشير إلى دور `admin`
- تأكد من أن جدول `roles` يحتوي على دور `admin`
- إذا كان المستخدم موجود لكن بدون role، قم بتحديثه:
  ```php
  $user = App\Models\User::where('email', 'admin@example.com')->first();
  $adminRole = App\Models\Role::where('slug', 'admin')->first();
  $user->role_id = $adminRole->id;
  $user->save();
  ```

### 6. مشكلة 302 Redirect:

إذا كنت تحصل على `302 Found` عند محاولة الوصول إلى `/admin/dashboard`:

- **السبب:** المستخدم غير مسجل دخول أو ليس لديه صلاحيات admin
- **الحل:** سجل دخول أولاً من `/login` باستخدام البيانات أعلاه

