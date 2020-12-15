<?php 
$Session = Session::getInstance();
$errors = $Session->errors;
$errors = is_array($errors) ? $errors : [];
?>
<div class="container">
	<div class="login__wrap">
		<img src="<?= __ROOT__ ?>/images/logo-xl.png" alt="Villa Lujan" class="login__brand">
		<span class="login__label"> Sistema | Residentes Villa Lujan </span>
		<form class="login__form" action="<?= __ROOT__ ?>/login" method="post">
			<input class="login__input" type="text" name="user" placeholder="Propietario" autocomplete="off">
			<input class="login__input" type="password" name="password" placeholder="Contraseña" autocomplete="off">
			<input class="login__submit" type="submit" name="action" value="INGRESAR">
		</form>
		<?php foreach ($errors as $error): ?>
		<p  class="login__msg"><?= $error ?></p>
		<?php endforeach ?>
	</div>
</div>
