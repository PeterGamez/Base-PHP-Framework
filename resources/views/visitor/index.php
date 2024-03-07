<?php

$site['social'] = true; // กำหนดให้เว็บไซต์ใช้งาน Open Graph ได้
$site['cdn'] = []; // กำหนดให้เว็บไซต์ใช้งาน CDN ที่กำหนดได้
$site['name'] = config('site.name');
$site['desc'] = config('site.description');
$site['bot'] = '';
?>

<?= visitor_views('layouts/header') ?>

<body>
    <?= visitor_views('layouts/navbar') ?>

    <main class="container">
        Welcome to <?= config('site.name') ?>
    </main>

    <?= visitor_views('layouts/footer') ?>

    <?= visitor_views('layouts/footer_cdn') ?>
</body>

</html>