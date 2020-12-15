<?php 
$owners = DB::instance()->query(sprintf("SELECT CONCAT('%s', id) as url, id, username as user, name, lastname, tel, email, observations as obs, if(active, 'activo', 'inactivo') as status FROM users", __ROOT__ . '/admin/propietarios/' ))->result();
?>
<div class="container page-main">
	<div class="header">
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
			<h1 class="header__title">Usuarios</h1>
		</div>
		<?= get_session_errors('<p class="msg-error">%s</p>') ?>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
	</div>
	<div class="fieldset clear">
		<div class="bookings-info" id="users-table"></div>
	</div>
</div>

<script>
	admin.usersTable({cols: [['Usuario', 'user'], ['Nombre', 'name', 'lastname'], ['Estado', 'status'], ['Telefono', 'tel']], rows: <?= json_encode($owners) ?>});
</script>