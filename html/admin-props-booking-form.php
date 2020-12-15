<form class="container page-main" id="props-booking" method="post" action="<?= __ROOT__ ?>/admin/propiedades">
	<div>
		<div class="header">
			<p class="header__msg">Bienvenido <?= sprintf('%s, %s', $user->lastname, $user->name) ?></p>
			<h1 class="header__title">Alquila tu departamento</h1>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<div class="form-group-collection">
			<div class="clear">
				<div id="input-props"></div>
				<script>Admin.bookUser(<?= json_encode(['users' => $users, 'properties' => $props], JSON_NUMERIC_CHECK) ?>);</script>
			</div>
			<label class="form-group">
				<span>Desde :</span>
				<input type="text" name="from" readonly="true" class="date-picker" autocomplete="off" value="<?= get_form('from') ?>">
			</label>
			<label class="form-group">
				<span>Hasta :</span>
				<input type="text" name="to" readonly="true" class="date-picker" autocomplete="off" value="<?= get_form('to') ?>">
			</label>
			<label class="form-group">
				<span>Nombre :</span>
				<input type="text" name="name" autocomplete="off" value="<?= get_form('name') ?>">
			</label>
			<label class="form-group">
				<span>Tel :</span>
				<input type="text" name="tel" autocomplete="off" value="<?= get_form('tel') ?>">
			</label>
			<label class="form-group">
				<span>Dni :</span>
				<input type="text" name="dni" autocomplete="off" value="<?= get_form('dni') ?>">
			</label>
			<label class="form-group">
				<span>Email :</span>
				<input type="text" name="email" autocomplete="off" value="<?= get_form('email') ?>">
			</label>

			<button class="form-btn">RESERVAR</button>
		</div>
	</div>
</form>