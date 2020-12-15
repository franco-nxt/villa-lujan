<?php
define('__ROOT__', '/bookings');
define('__BASEDIR__', __DIR__);

include 'libs/configs.php';
include 'libs/constans.php';
include 'libs/functions.php';

require 'vendor/autoload.php';

spl_autoload_register('load_class', true, true);

$dispatcher = FastRoute\simpleDispatcher('__simple_dispatcher_handler__');
$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], resolve_uri());

switch ($routeInfo[0]) 
{
	case FastRoute\Dispatcher::FOUND: 
	call_user_func_array($routeInfo[1], $routeInfo[2]); 
	break;

	default: 
	trhow_404(); 
	break;
}

function __simple_dispatcher_handler__($router)
{
	$router->get(__ROOT__, 'Index::main');
	
	$router->get(__ROOT__ . '/login', 'Index::main');
	$router->post(__ROOT__ . '/login', 'Index::login');

	$router->addRoute(['GET', 'POST'], __ROOT__ . '/logout', 'Index::logout');

	$router->get(__ROOT__ . '/quincho', 'Lodge::index');
	$router->post(__ROOT__ . '/quincho', 'Lodge::booking');
	
	$router->get(__ROOT__ . '/quincho/info', 'Lodge::info');
	$router->post(__ROOT__ . '/quincho/info', 'Lodge::update_info');

	$router->get(__ROOT__ . '/propiedades', 'Props::index');
	$router->post(__ROOT__ . '/propiedades', 'Props::booking');
	
	$router->get(__ROOT__ . '/propiedades/info', 'Props::info');
	
	$router->post(__ROOT__ . '/propiedades/eliminar', 'Props::delete');
	
	$router->get(__ROOT__ . '/admin/propietarios', 'Admin::owners');
	
	$router->get(__ROOT__ . '/admin/propietarios/nuevo', 'Admin::create_owner');
	$router->post(__ROOT__ . '/admin/propietarios/nuevo', 'Admin::post_create_owner');
	
	$router->get(__ROOT__ . '/admin/propietarios/{id:\d+}', 'Admin::owner');
	$router->post(__ROOT__ . '/admin/propietarios/{id:\d+}', 'Admin::post_owner');

	$router->get(__ROOT__ . '/admin/reglamentos', 'Admin::regulations');
	$router->get(__ROOT__ . '/admin/reglamentos/nuevo', 'Admin::new_regulation');
	$router->post(__ROOT__ . '/admin/reglamentos/nuevo', 'Admin::create_regulation');
	$router->get(__ROOT__ . '/admin/reglamento/editar/{id:\d+}', 'Admin::edit_regulation');
	$router->post(__ROOT__ . '/admin/reglamento/editar/{id:\d+}', 'Admin::update_regulation');

	$router->get(__ROOT__ . '/admin/propiedades', 'Admin::booking_property_form');
	$router->post(__ROOT__ . '/admin/propiedades', 'Admin::booking_property_post');
	$router->get(__ROOT__ . '/admin/propiedades/info', 'Admin::props_info');
	
	$router->get(__ROOT__ . '/admin/quincho', 'Admin::booking_lodge_form');
	$router->get(__ROOT__ . '/admin/quincho/info', 'Admin::lodge_info');

	$router->get(__ROOT__ . '/watcher/quincho', 'Watcher::lodge');
	$router->get(__ROOT__ . '/watcher/propiedades', 'Watcher::bookings');
}