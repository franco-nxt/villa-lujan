<div class="container page-main" method="post" action="<?= __ROOT__ ?>/quincho/info">
	<div class="header">
		<p class="header__msg">Bienvenido <?= $user->lastname ?>, <?= $user->name ?></p>
		<h1 class="header__title">Reservás del quincho</h1>
	</div>
	<div class="fieldset clear">
		<?php if (empty($bookings)) : ?>
			<h3>Al momento no hay reservas para hoy.</h3>
		<?php endif ?>
		<?php foreach ($bookings as $booking) : ?>
			<div class="booking">
				<h1 class="booking__detail__title"><?= date('d/m/Y', strtotime($booking->date_in)) ?> - Turno <?= $booking->time_shift ?></h1>
				<div class="booking__detail">
					<h3 class="booking__detail__title">Ocupante: <?= $booking->occupant_name ?></h3>
					<?php if ($booking->occupant_email) : ?>
						<p class="booking__detail__info"><strong>Email:</strong> <?= $booking->occupant_email ?></p>
					<?php endif ?>
					<?php if (!empty($booking->props)) : ?>
						<p class="booking__detail__info"><strong>Unidades: </strong><?= implode(', ', array_column($booking->props, 'name')) ?></p>
					<?php endif ?>
				</div>
				<div class="booking__occupant">
					<h3 class="booking__detail__title">Invitados</h3>
					<?php if (!empty($booking->guests)) : ?>
						<ol>
							<?php foreach (json_decode($booking->guests) as $guest) : ?>
								<li><?= $guest ?></li>
							<?php endforeach ?>
						</ol>
					<?php else : ?>
						<u>No hay invitados registrados.</u>
					<?php endif ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>