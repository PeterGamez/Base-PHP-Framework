<!DOCTYPE html>
<html lang="<?= config('site.lang') ?>">

<head>
    <title><?= $site['name'] ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubDomains; preload">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="<?= $site['name'] ?>">
    <meta name="theme-color" content="#333399">
    <?php
    if ($site['social'] == true) {
    ?>
        <meta name="description" content="<?= $site['desc'] ?>">
        <meta name="keywords" content="">
        <meta property="og:title" content="<?= $site['name'] ?>">
        <meta property="og:description" content="<?= $site['desc'] ?>">
        <meta property="og:site_name" content="<?= config('site.name') ?>">
        <meta property="og:url" content="<?= url() ?>">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="th_TH">
        <meta property="og:image" content="<?= config('site.logo.128') ?>">
        <meta property="og:image:width" content="250">
        <meta property="og:image:height" content="250">
        <meta name="twitter:card" content="app">
        <meta name="twitter:title" content="<?= $site['name'] ?>">
        <meta name="twitter:description" content="<?= $site['desc'] ?>">
        <meta name="twitter:image" content="<?= config('site.logo.128') ?>">
    <?php
    }
    if ($site['bot']) {
    ?>
        <meta name="robots" content="<?= $site['bot'] ?>">
        <meta name="googlebot" content="<?= $site['bot'] ?>">
        <meta name="googlebot-news" content="<?= $site['bot'] ?>">
    <?php
    }
    ?>
    <link rel="canonical" href="<?= url() ?>">

    <!-- Site icon -->
    <link rel="icon" href="<?= config('site.logo.64') ?>" type="image/png" sizes="64x64">
    <link rel="icon" href="<?= config('site.logo.128') ?>" type="image/png" sizes="128x128">
    <link rel="shortcut icon" href="<?= config('site.logo.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= config('site.logo.64') ?>">

    <?= visitor_views('layouts/header_cdn') ?>
</head>