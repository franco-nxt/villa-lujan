<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" /><meta name="format-detection" content="telephone=no" />
	<title><?= _global('__title__') ?></title>
	<link rel="stylesheet" href="<?= __ROOT__ ?>/dist/css/app.css">
	<link rel="stylesheet" href="<?= __ROOT__ ?>/dist/css/admin.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700" rel="stylesheet">
</head>
<body>
	<script type="text/javascript" src="<?= __ROOT__ ?>/dist/js/Admin.js"></script>
	<script type="text/javascript" src="<?= __ROOT__ ?>/dist/js/Lodge.js"></script>
	<div class="page page-<?= _global('__page__') ?>">
		<?php if (!empty($user)): switch (_global('__NAVBAR__')) :
			case 'watcher': include __BASEDIR__ . '/html/navbar-watcher.php'; break;
			case 'admin': include __BASEDIR__ . '/html/navbar-admin.php'; break;
			default: include __BASEDIR__ . '/html/navbar.php'; break;
		endswitch; endif ?>
		<?= _global('__content__') ?>
		<?php include __BASEDIR__ . '/html/footer.php' ?>
	</div>
	<script type="text/javascript" src="<?= __ROOT__ ?>/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="<?= __ROOT__ ?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= __ROOT__ ?>/dist/js/App.js"></script>
	<!-- <script type="text/javascript" src="/dist/js/app.js"></script> -->
</body>
</html>