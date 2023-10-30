# Base PHP Framework

## ขั้นตอนการติดตั้ง

### กรณีมี path public
- ไม่ต้องทำอะไรเพิ่มเติม

### กรณีไม่มี path public
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

## กรณีใช้งานระบบ Trash (default false)
- สำหรับใช้งานระบบ Trash
1. ให้แก้ไขไฟล์ config/database ดังนี้
```php
'enable_trash' => true
```
1. เพื่ม Scheduled Tasks `cron 0 0 * * *`


## การใช้งาน helper
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