# Base PHP Framework

<img src="https://img.shields.io/github/license/petergamez/base-php-framework" alt="License">

สารบัญ
- [โครงสร้างโฟลเดอร์](#โครงสร้างโฟลเดอร์)
- [ข้อกำหนดของเซิร์ฟเวอร์](#ข้อกำหนดของเซิร์ฟเวอร์)
- [ขั้นตอนการติดตั้ง](#ขั้นตอนการติดตั้ง)
- [ใช้งานระบบ Trash (default false)](#ใช้งานระบบ-trash-default-false)
- [การใช้งาน Helper](#การใช้งาน-helper)
- [การใช้งาน Route](#การใช้งาน-route)
- [การใช้งาน Model](#การใช้งาน-model)
- [กาารใช้งาน Validate](#กาารใช้งาน-validate)

## โครงสร้างโฟลเดอร์
| โฟลเดอร์             | คำอธิบาย 
| -                   | - 
| app                 | ระบบหลักของโปรเจค 
| app/Controllers     | ส่วนประมวลผล
| app/Core            | ส่วนหลักของระบบ
| app/Models          | ส่วนติดต่อกับฐานข้อมูล
| config              | ไฟล์การกำหนดค่าของโปรเจค
| database            | ฐานข้อมูล
| database/dumps      | ไฟล์สำรองข้อมูล
| database/migrations | การสร้างฐานข้อมูล
| database/seeds      | การเพิ่มข้อมูลเริ่มต้น
| public              | ไฟล์สาธารณะที่สามารถเข้าถึงได้
| public/resources    | เก็บไฟล์สาธารณะเช่น CSS, JS, Image
| resources           | การแสดงผลต่าง ๆ
| resources/api       | การเแสดงผล API
| resources/views     | การแสดงผล View
| routes              | กำหนดเส้นทางของเว็บไซต์
| system              | ไฟล์ระบบของเฟรมเวิร์ค

## ข้อกำหนดของเซิร์ฟเวอร์
- PHP 8.1 ขึ้นไป
- ฐานข้อมูล MySQL หรือ MariaDB
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

## การใช้งาน Helper
- ตรวจสอบคำสั่งทั้งหมด
```bash
php helper
```
- สร้าง config ใหม่จาก .example โดยใช้คำสั่ง
```bash
php helper make:config
```
- สร้าง model ใหม่ โดยใช้คำสั่ง
```bash
php helper make:model <ชื่อ model>
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
- redirect

ตัวอย่างการใช้งาน
```php
Route::get('/', function (Request $request) {
    return 'Hello World';
});

Route::get('/home', [HomeController::class, 'index']);

Route::get('/user/{id}', function (Request $request, string $id) {
    return 'User ID: ' . $id;
});

Route::get('/request/*', function (Request $request, string $patten = null) {
    Respond::json($request);
});

Route::group('admin', function () {
    Route::get('/', function (Request $request) {
        return 'Admin';
    });
    Route::post('/create', [AdminController::class, 'create']);
    Route::match(['post','put'], '/update/{id}', function (Request $request, string $id) {
        return 'Update ID: ' . $id;
    });
});

Route::redirect('/here', '/there');
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

// กรณีใช้งานระบบ Trash
Account::restore()
    ->where('id', 1)
    ->run();

// กรณีใช้งานระบบ Trash
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