<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title><?= $title; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">

	<!-- facebook -->
	<meta property="og:title" content="<?= $Row['blog_title']; ?> | <?= $__site['name']; ?>" />
	<meta property="og:url" content="<?= $__site['url']; ?><?= iu_get_page_url(); ?>" />
	<meta property="og:site_name" content="<?= $__site['name']; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:url" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:secure_url" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:type" content="image/gif" />
	<meta property="og:image:width" content="1500" />
	<meta property="og:image:height" content="1500" />
	<meta property="og:description" content="<?= iu_convert_image_links(iu_read_more($Row['blog_message'], 120, '...Read More</a>')); ?>" />

	<!-- twitter -->
	<meta name="twitter:account_id" content="<?= $__site['twitter-url']; ?>" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@<?= strtolower($__site['name']); ?>" />
	<meta name="twitter:title" content="<?= $Row['blog_title']; ?> | <?= $__site['name']; ?>" />
	<meta name="twitter:description" content="<?= iu_convert_image_links(iu_read_more($Row['blog_message'], 120, '...Read More</a>')); ?>" />
	<meta name="twitter:image:src" content="https://recurly.com/img/og-image.gif" />
	<meta name="twitter:image:width" content="1500" />
	<meta name="twitter:image:height" content="1500" />
</head>