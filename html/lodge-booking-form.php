<form class="container page-main" method="post" action="<?= __ROOT__ ?>/quincho">
	<div id="lodge-form"></div>
	<!-- <div class="row">
		<div class="col-sm-6">
			<div class="header">
				<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
				<h1 class="header__title">Reservá tu turno <br>para el Quincho</h1>
			</div>
			
		</div>
		<div class="col-sm-6">
			<div id="calendar">
			</div>
			<div class="calendar__samples">
				<span class="calendar__sample">
					<img src="<?= __ROOT__ ?>/images/sample-busy.png" alt=""> Ocupado
				</span>
				<span class="calendar__sample">
					<img src="<?= __ROOT__ ?>/images/sample-free.png" alt=""> Disponible
				</span>
				<span class="calendar__sample">
					<img src="<?= __ROOT__ ?>/images/sample-half-busy.png" alt=""> Disponible 1/2 turno
				</span>
			</div>
		</div>
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
				<span>Turno :</span>
				<select name="time">
					<option value="0">Mediodia</option>
					<option value="1">Noche</option>
				</select>
			</label>
			<label class="form-group">
				<span>Nombre :</span>
				<input type="text" name="name" value="<?= $user->lastname ?>, <?= $user->name ?>" readonly>
			</label>
			<label class="form-group">
				<span>Dia :</span>
				<input type="text" name="date" id="date" readonly>
			</label>
			<button class="form-btn">RESERVAR</button>
			<div class="clear">
				<a href="#" class="form-link add-people" id="add-guests">AGREGAR INVITADOS <small></small></a>
			</div>
			<div class="clear" id="guests-list">
			</div>
		</div>
	</div> -->
</form>
<script>
	Lodge.calendar({ 
		$input: document.querySelector('#date'), 
		max: '<?= date("F d, Y", strtotime("+1 month")) ?>', 
		min: '<?= date("F d, Y") ?>', 
		bookings: <?= json_encode($dates) ?>, 
		properties: <?= json_encode($props) ?>,
		user: <?= json_encode($user) ?>,
		errors: '<?= get_session_errors('<p class="msg-error">%s</p>') ?>',
		success: '<?= get_session_msgs('<p class="msg-success">%s</p>') ?>'
	}, '#lodge-form')
	// window.calendarconfig = {
	// 	$el: '#calendar',
	// 	$input: '#date',
	// 	max: '<?= date("F d, Y", strtotime("+1 month")) ?>',
	// 	min: '<?= date("F d, Y") ?>',
	// 	bookings: <?= json_encode($dates) ?>
	// }
</script>