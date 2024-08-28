# Base PHP Framework

<p align="left">
<img src="https://img.shields.io/github/license/petergamez/base-php-framework" alt="License">
<img src="https://img.shields.io/badge/php->= 8.1-8892BF.svg?logo=php" alt="PHP Verison">
<img src="https://img.shields.io/badge/version-1.0.0-4AC51C.svg" alt="Verison">
</p>

สารบัญ
- [โครงสร้างโฟลเดอร์](#โครงสร้างโฟลเดอร์)
- [ข้อกำหนดของเซิร์ฟเวอร์](#ข้อกำหนดของเซิร์ฟเวอร์)
- [ขั้นตอนการติดตั้ง](#ขั้นตอนการติดตั้ง)
- [การใช้งาน Helper](#การใช้งาน-helper)
- [ใช้งานระบบ Trash (default false)](#ใช้งานระบบ-trash-default-false)
- [การใช้งาน Route](#การใช้งาน-route)
- [การใช้งาน Model](#การใช้งาน-model)
- [กาารใช้งาน Validate](#กาารใช้งาน-validate)
- [ใบอนุญาต](#ใบอนุญาต)

## โครงสร้างโฟลเดอร์
| โฟลเดอร์             | คำอธิบาย 
| ------------------- | -------------------
| app                 | ระบบหลักของโปรเจค 
| app/Controllers     | ส่วนประมวลผล
| app/Core            | ส่วนหลักของระบบ
| app/Models          | ส่วนติดต่อกับฐานข้อมูล
| config              | การกำหนดค่าของโปรเจค
| database            | ฐานข้อมูล
| database/dumps      | ไฟล์สำรองข้อมูล
| database/migrations | การสร้างฐานข้อมูล
| database/seeds      | การเพิ่มข้อมูลเริ่มต้น
| public              | โฟลเดอร์สาธารณะที่สามารถเข้าถึงได้
| public/resources    | ไฟล์สาธารณะที่สามารถเรียกใช้ได้ผ่านคำสั่ง resources() เช่น CSS, JS, Image
| resources           | การแสดงผลต่าง ๆ
| resources/api       | การแสดงผล API ผ่านคำสั่ง api()
| resources/views     | การแสดงผล View ผ่านคำสั่ง view()
| routes              | กำหนดเส้นทางของเว็บไซต์
| system              | ระบบหลักของเฟรมเวิร์ค

## ข้อกำหนดของเซิร์ฟเวอร์
- PHP 8.1 ขึ้นไป
- ฐานข้อมูล MySQL หรือ MariaDB (แนะนำ)
- DNS ผ่าน Cloudflare (แนะนำ)
- ประเภทเซิร์ฟเวอร์ Apache หรือ Nginx
- ทำงานผ่าน Root URL

## ขั้นตอนการติดตั้ง

### กรณีมี public path
- ไม่ต้องทำอะไรเพิ่มเติม

### กรณีไม่มี public path
- สำหรับ apache ให้เพื่มไฟล์ .htaccess ดังนี้ ใน root directory
```bash
RewriteEngine on

RewriteCond %{REQUEST_URI} !^public
RewriteRule ^(.*)$ public/$1 [L]
```
- สำหรับ nginx ให้แก้ไขไฟล์ config ดังนี้
```bash
location / {
    try_files $uri $uri/ /public/index.php?$is_args$args;
    autoindex on;
}
```

## การใช้งาน Helper
- ตรวจสอบคำสั่งทั้งหมด
```bash
php helper
```

## ใช้งานระบบ Trash (default false)
- สำหรับใช้งานระบบ Trash
1. ให้แก้ไขไฟล์ config/database ดังนี้
```php
'enable_trash' => true
```
2. เพื่ม Scheduled Tasks `cron 0 0 * * *`
```bash
php helper model:clearAllTrash
```

## การใช้งาน Route
ใช้งานผ่านไฟล์ routes/web.php หรือ routes/api.php

คำสั่งทั้งหมด
- get
- post
- put
- patch
- delete
- options
- match
- any
- group
- redirect

ตัวอย่างการใช้งาน
```php
Route::get('/', function (Request $request) {
    Respond::text('Hello World');
});

Route::get('/home', [HomeController::class, 'index']);

Route::get('/user/{id}', function (Request $request, string $id) {
    Respond::text('User ID: ' . $id);
});

Route::get('/request/*', function (Request $request, string $patten = null) {
    Respond::json($request);
});

Route::group('admin', function (Request $request) {
    Route::get('/', function (Request $request) {
        Respond::text('Admin');
    });
    Route::post('/create', [AdminController::class, 'create']);
    Route::match(['post','put'], '/update/{id}', function (Request $request, string $id) {
        Respond::text('Update ID: ' . $id);
    });
});

Route::redirect('/here', '/there');

Respond::status(404)::text('404 Not Found');
```

## การใช้งาน Model
ตัวอย่างการใช้งาน
```php
Account::create([
    'name' => 'Admin',
    'email' => 'admin@example.com'
])->run();

Account::find()
    ->select(
        'id',
        'name',
    )
    ->where('id', 1)
    ->get();

Account::update()
    ->set('name', 'Owner')
    ->set('email', 'Owner')
    ->where('id', 1)
    ->run();

Account::delete()
    ->where('id', 1)
    ->run();

/* กรณีใช้งานระบบ Trash */
Account::restore()
    ->where('id', 1)
    ->run();

/* กรณีใช้งานระบบ Trash */
Account::forceDelete()
    ->where('id', 1)
    ->run();
```

## กาารใช้งาน Validate
method ที่ใช้งานได้
- required
- email
- number
- hostname
- url
- ip
- ipv4
- ipv6
- privateIP
- publicIP
- mac
- uuid
- min:5
- max:10
- in:1,2,3
- notIn:1,2,3

ตัวอย่างการใช้งาน
```php
Route::post('/create', function (Request $request) {
    $validate = $request->validate([
        'name' => 'required|min:3|max:10',
        'email' => 'required|email'
    ]);
    if ($validate->error) {
        Respond::json($validate);
    }
});
```

## ใบอนุญาต
Base PHP Framework เป็นซอฟต์แวร์โอเพ่นซอร์สที่ได้รับอนุญาตภายใต้ [MIT license](LICENSE).