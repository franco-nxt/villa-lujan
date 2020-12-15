<div class="container page-main">
	<div class="header">
		<p class="header__msg">Bienvenido Lorenzo, Ezequiel</p>
		<h1 class="header__title">Formulario de usuarios</h1>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<form class="fieldset clear" method="post">
		<div class="form-group-collection">
			<label class="form-group">
				<span>Usuario :</span>
				<input type="text" name="user" value="<?= get_form('user') ?>">
			</label>
			<label class="form-group">
				<span>Contraseña :</span>
				<input type="text" name="pass">
			</label>
			<label class="form-group">
				<span>Nombre :</span>
				<input type="text" name="name" value="<?= get_form('name') ?>">
			</label>
			<label class="form-group">
				<span>Apellido :</span>
				<input type="text" name="lastname" value="<?= get_form('lastname') ?>">
			</label>
			<label class="form-group">
				<span>Tel :</span>
				<input type="text" name="tel" value="<?= get_form('tel') ?>">
			</label>
			<label class="form-group">
				<span>Email :</span>
				<input type="text" name="email" value="<?= get_form('email') ?>">
			</label>
			<label class="form-group form-group-lg">
				<span>Obsevaciones :</span>
				<textarea name="obs" cols="30" rows="10"><?= get_form('obs') ?></textarea>
			</label>
			<label class="form-group">
				<span>Estado :</span>
				<select name="status">
					<?php $status = get_form('status') ?>
					<option value="1" <?= $status !== 0 ? 'selected="selected"' : null ?>>Activo</option>
					<option value="0" <?= $status === 0 ? 'selected="selected"' : null ?>>Inactivo</option>
				</select>
			</label>
			<label for="_watcher" class="form-checkbox">
				<input type="checkbox" id="_watcher" value="1" name="watcher" <?= get_form('watcher') ? 'checked=""' : '' ?>  />
				<span>Vigilancia</span>
			</label>
			<?php if (!empty($owner->properties)): ?>
			<div class="clear">
				<p>Propiedades registradas<!-- <br><small>(<span style="position:relative;padding-right: 15px;"><span class="form-checkbox-inactive">*</span></span>)Propiedades inactivas</small> --></p>
				<?php foreach ($owner->properties as $prop): ?>
				<label for="_<?= $prop->id ?>" class="form-checkbox">
					<input type="checkbox" id="_<?= $prop->id ?>" value="<?= $prop->id ?>" name="props[]" checked="true" />
					<span><?= $prop->name ?></span>
					<?php if (empty($prop->active)): ?>
					<span class="form-checkbox-inactive">*</span>
					<?php endif ?>
				</label>
				<?php endforeach ?>
			</div>
			<?php endif ?>
			<?php if (!empty($properties)): ?>
			<div class="clear">
				<p>Propiedades<!--  <br><small>(<span style="position:relative;padding-right: 15px;"><span class="form-checkbox-inactive">*</span></span>)Propiedades inactivas</small> --></p>
				<?php foreach ($properties as $prop): ?>
				<label for="_<?= $prop->id ?>" class="form-checkbox">
					<input type="checkbox" id="_<?= $prop->id ?>" value="<?= $prop->id ?>" name="props[]" />
					<span><?= $prop->name ?></span>
					<?php if (empty($prop->active)): ?>
					<span class="form-checkbox-inactive">*</span>
					<?php endif ?>
				</label>
				<?php endforeach ?>
			</div>
			<?php endif ?>

			<button class="form-btn">GUARDAR</button>
			<a href="<?= __ROOT__ ?>/admin/propietarios" class="form-link">CANCELAR</a>
		</div>
	</form>
</div>

