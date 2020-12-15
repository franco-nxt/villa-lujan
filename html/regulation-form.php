<form class="container page-main" id="props-booking" method="post" enctype="multipart/form-data">
	<div>
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
			<?php if (empty($regulation)): ?>
			<h1 class="header__title">Agregar nuevo reglamento</h1>
			<?php else: ?>
			<h1 class="header__title">Editar reglamento</h1>
			<?php endif ?>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<div class="form-group-collection">
			<label class="form-group">
				<span>Nombre :</span>
				<input type="text" name="name" autocomplete="off" value="<?= empty($regulation->name) ? null : $regulation->name ?>"/>
			</label>
			<label class="form-group">
				<span>Archivo :</span>
				<input type="file" name="file" style="top: 3px;position: relative;" />
			</label>
			<?php if ($regulation): ?>
			<button class="form-btn form-btn-del" name="eliminar">ELIMINAR</button>
			<?php endif ?>
			<button class="form-btn" name="guardar">GUARDAR</button>
		</div>
	</div>
</form>