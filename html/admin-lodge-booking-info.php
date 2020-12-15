<div class="container page-main">
	<div class="header">
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
			<h1 class="header__title">Reporte del quincho</h1>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<?php if (empty($bookings)): ?>
			<h3>NO SE ENCONTRARON REGISTROS</h3>
		<?php else: ?>
		<div class="bookings-info" id="lodge-info"></div>						
		<script>Admin.lodge(<?= json_encode([ 'bookings' => $bookings]) ?>);</script>
		<?php endif ?>
	</div>
</div>
