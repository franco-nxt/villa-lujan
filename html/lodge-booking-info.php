<form class="container page-main" method="post" action="<?= __ROOT__ ?>/quincho/info">
	<?php if (empty($booking->id)): ?>
		<div class="header">
			<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
		</div>
		<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
		<p class="msg-success">
			NO HAY RESERVAS PENDIENTES
		</p>
		<?php else: ?>
			<div >
				<div class="header">
					<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
					<h1 class="header__title">Reservá activa</h1>
				</div>
				<?= get_session_errors('<p class="msg-error">%s</p>') ?>
				<?= get_session_msgs('<p class="msg-success">%s</p>') ?>
				<p class="msg-success">
					Quincho reservado para el dia <?= $booking->date ?> en el turno <?= boolval($booking->time) ? 'de la noche' : 'del mediodía' ?>.
				</p>
			</div>
			<div class="fieldset clear">
				<div class="form-group-collection">
					<div class="clear" id="props-list">
						<?php foreach ($props as $prop): ?>
							<label class="form-checkbox"><span><?= $prop->name ?></span></label>
						<?php endforeach ?>
					</div>
					<input type="hidden" value="<?= $booking->id ?>" name="booking">
					<label class="form-group">
						<span>Nombre :</span>
						<input type="text" name="name" value="<?= $booking->name ?>" readonly>
					</label>
					<button class="form-btn">GUARDAR</button>
					<form method="POST" class="clear" action="eliminar">
						<button class="form-btn  form-btn-del" name="delete" value="<?= $booking->id ?>">ELIMINAR RESERVA</button>
						<a href="#" class="form-link add-people" id="add-guests">AGREGAR INVITADOS <small><?= empty($guests) ? null : count($guests) . '/13' ?></small></a>
					</form>
					<div class="clear" id="guests-list">
						<?php foreach ($guests as $guest): ?>
							<label class="form-group"><span>Nombre: </span><input type="text" name="guests[]" value="<?= $guest ?>"></label>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		<?php endif ?>
	</form>