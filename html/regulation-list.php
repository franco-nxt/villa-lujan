<div class="container page-main">
	<div>
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Reglamentos</th>
					<th></th>
					<th style="text-align:right;"><a href="<?= __ROOT__ ?>/admin/reglamentos/nuevo" class="form-btn">Cargar nuevo</a></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($regulations as $reg): ?>
				<tr>
					<td><?= $reg->name ?></td>
					<td><a href="<?= $reg->src ?>" target="_blank">Ver</a></td>
					<td><a href="<?= __ROOT__ ?>/admin/reglamento/editar/<?= $reg->id ?>">Editar</a></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>