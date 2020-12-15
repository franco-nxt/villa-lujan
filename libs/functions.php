<?php 

function handle_request_uri($rgx, $callable) {
	$is_match = preg_match('#^\/?' . __ROOT__ . $rgx . '\/?$#', $_SERVER['REQUEST_URI'], $matches);

	if($is_match){
		unset($matches[0]);
		call_user_func_array($callable,  $matches);
	}
}

function pre_dump(){
	echo '<pre>';
	array_map('var_dump', func_get_args());
	echo '</pre>';
}

function load_class($class_name)
{	
	$module = __BASEDIR__ . '/modules/' . $class_name . '.php';
	$class = __BASEDIR__ . '/classes/' . $class_name . '.php';

	if(file_exists($class)){
		include $class;
	}
	elseif(file_exists(strtolower($module))){
		include $module;
	}
}

function trhow_404()
{
	header("HTTP/1.0 404 Not Found");
}

function redirect_login()
{
	header(sprintf("Location: %s://%s%s/login", $_SERVER['REQUEST_SCHEME'], rtrim($_SERVER['HTTP_HOST'], '/'), __ROOT__));
	exit;
}

function redirect($extra = null)
{
	header(sprintf("Location: %s://%s%s/%s", $_SERVER['REQUEST_SCHEME'], rtrim($_SERVER['HTTP_HOST'], '/'), __ROOT__, $extra));
	exit;
}


/**
 * Asigna y devuelve variables globales.
 * Es util para asignar valores fuera del scope.
 *
 * @uses 
 * @param  String $key   Clave del valor a asignar o que se esta pidiendo.
 * @param  $value Valor que se asigna a $key.
 * @return El valor de la variable almacenada en $key
 */
function _global($key, $value = null){
	if (isset($key, $value) && $key) {
		$GLOBALS[$key] = $value;
	}
	elseif (isset($GLOBALS[$key])) {
		return $GLOBALS[$key];
	}

	return null;
}

function filter_input_post($rules, $alias = [])
{
	$V = new Validator($_POST);
	$fields = [];

	foreach ($rules as $field => $rule) {
		$explode_field = explode('.', $field);
		$fields[$explode_field[0]] = true;

		if (is_array($rule)) {
			foreach ($rule as $k => $v) {
				if (is_array($v)) {
					$v = implode(",", $v);
				}

				if (is_string($k) && is_scalar($v)) {
					$rule[$k] = $k . ':' . $v;
				}
			}

			$rule = implode("|", array_unique($rule));
		}

		$regulation = explode("|", $rule);

		foreach ($regulation as $el) {
			$r = explode(':', $el);
			$params = [$r[0], $field];

			switch ($r[0]) {
				case 'integer':
				case 'required':
				if(!empty($r[1])){
					$params[] = true;
				}
				break;

				case 'lengthBetween': 
				if(!empty($r[1])){
					foreach (explode(',', $r[1]) as $v) {
						$params[] = $v;
					}
				}
				break;

				case 'in': 
				case 'notIn': 
				case 'subset': 
				case 'creditCard': 
				if(!empty($r[1])){
					$params[] = explode(',', $r[1]);
				}
				break;

				case 'equals':
				case 'different':
				case 'length':
				case 'lengthMin':
				case 'lengthMax':
				case 'min':
				case 'max':
				case 'regex':
				case 'date':
				case 'dateFormat':
				case 'dateBefore':
				case 'dateAfter':
				case 'contains':
				if(!empty($r[1])){
					$params[] = $r[1];
				}
				break;
			}

			call_user_func_array([$V, 'rule'], $params);
		}
	}

	if($V->validate()) {
		return array_intersect_key($V->data(), $fields);
	} 
	else {
		$errors = [];
		
		foreach ($V->errors() as $error) {
			$errors = array_merge($errors , $error);
		}

		throw new VLException(str_replace( array_keys($alias), array_values($alias), json_encode($errors) ));
	}
}

function get_user()
{
	$Session = Session::getInstance();
	
	if(empty($Session->USER->deadline)){
		redirect_login();
	}
	else{
		$deadline = $Session->USER->deadline;

		if($deadline < time()){
			$Session->reset();
			
			$Session->errors = ['Es necesario volver a loguarse.'];

			redirect_login();
		}
		else{
			$Session->USER->deadline = time() + SESSION_TIME;
		}
	}

	return $Session->USER;
}

function save_form($form)
{
	$Session = Session::getInstance();
	
	foreach ($form as $k => $v) {
		$Session->{'_session_form_' . $k} = $v;
	}
}

function get_form($key)
{
	return Session::getInstance()->{'_session_form_' . $key};
}

function get_session_errors($wrap = '<div>%s</div>')
{
	$errors = Session::getInstance()->errors; 
	$res = '';

	if(!empty($errors)){
		foreach ($errors as $error){
			$res .= sprintf($wrap, $error);
		}
	}

	return $res;
}

function get_session_msgs($wrap = '<div>%s</div>')
{
	$msgs = Session::getInstance()->msgs; 
	$res = '';

	if(!empty($msgs)){
		foreach ($msgs as $msg){
			$res .= sprintf($wrap, $msg);
		}
	}

	return $res;
}


function handle_exception($e)
{
	if ($e instanceof VLException) {
		Session::getInstance()->errors = json_decode($e->getMessage());
	}
	else{
		Session::getInstance()->errors = ['Ocurrio un error a nivel de servidor. Vuelva a intenrarlo.'];
		$log_msg = json_encode($e->getTrace());
		$name = get_class($e).date('_H_i__d_M_Y');
		$log_filename = "logs";

		if (!file_exists($log_filename)) {
			mkdir($log_filename, 0777, true);
		}

		if (!$name) {
			$name = date('d-M-Y');
		}

		$log_file_data = sprintf("%s/%s/log_%s.json", __BASEDIR__, $log_filename, $name);
		
		file_put_contents($log_file_data, "$log_msg\n", FILE_APPEND);
	}
}

function use_view($file, $pagename = 'home', $args = [])
{
	if (is_array($pagename)) {
		extract($pagename);
		_global('__page__', 'home');
	}
	elseif(is_array($args)){
		extract($args);
		_global('__page__', $pagename);
	}


	ob_start();
	
	include __BASEDIR__ . '/html/' . $file . '.php';

	_global('__content__', ob_get_clean());

	include __BASEDIR__ . '/tpl/main.php';
}

function resolve_uri()
{
	$uri = $_SERVER['REQUEST_URI'];

	if (false !== $pos = strpos($uri, '?')) {
		$uri = substr($uri, 0, $pos);
	}

	return rtrim(rawurldecode($uri), '/');
}

function review_request()
{
	
}

