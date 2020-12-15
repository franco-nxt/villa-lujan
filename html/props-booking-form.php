<form class="container page-main" id="props-booking" method="post" action="<?= __ROOT__ ?>/propiedades">
	<div>
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
			<h1 class="header__title">Alquila tu departamento</h1>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<div class="form-group-collection">
			<div class="clear">
				<?php if (empty($props)): ?>
					<h3>No hay propiedades para alquilar.</h3>
				<?php endif ?>
				<?php foreach ($props as $prop): ?>
				<label for="_<?= $prop->id ?>" class="form-checkbox">
					<input type="checkbox" id="_<?= $prop->id ?>" value="<?= $prop->id ?>" name="props[]" />
					<span><?= $prop->name ?></span>
				</label>
				<?php endforeach ?>
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
			<label class="form-group">
				<span>Patente :</span>
				<input type="text" name="observations" autocomplete="off" value="<?= get_form('observations') ?>">
			</label>

			<button class="form-btn">RESERVAR</button>
		</div>
	</div>
</form>