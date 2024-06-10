<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
  <title><?= session()->get('SEO_title') ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Next Step Up">

  <meta name="description" content="<?= session()->get('SEO_description') ?>">
  <meta name="keywords" content="<?= session()->get('SEO_keywords') ?>">
  <meta property="og:locale" content="pt_BR" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?= session()->get('SEO_title') ?>" />
  <meta property="og:description" content="<?= session()->get('SEO_description') ?>" />
  <meta property="og:url" content="" />
  <meta property="og:site_name" content="<?= session()->get('SEO_title') ?>" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:description" content="<?= session()->get('SEO_description') ?>" />
  <meta name="twitter:title" content="<?= session()->get('SEO_title') ?>" />

  <meta name='robots' content='max-image-preview:large' />
  <link rel="canonical" href="<?= base_url() ?>" />
  <link rel='shortlink' href='<?= base_url() ?>' />

  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('/apple-touch-icon.png') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('/favicon-16x16.png') ?>">
  <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">
  <link rel="mask-icon" href="<?= base_url('/safari-pinned-tab.svg') ?>" color="#5bbad5">
  <link rel="shortcut icon" href="<?= base_url('/favicon.ico') ?>">
  <meta name="msapplication-TileColor" content="#834283">
  <meta name="theme-color" content="#ffffff">


	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<link href="/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/css/datatables.min.css" rel="stylesheet" />
	<link href="/css/slick.css" rel="stylesheet" />
	<link href="/css/slick-theme.css" rel="stylesheet" />
	<link href="/css/sweetalert2.css" rel="stylesheet" />
	<link href="/css/site.css" rel="stylesheet" />
</head>

<body>

