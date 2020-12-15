<?php 

class Admin
{
	public static function __callStatic($name, $arguments)
	{
		$user = get_user();

		if(empty($user->admin)){
			trhow_404();
		}
		else{
			_global('__NAVBAR__', strtolower(__CLASS__));
			call_user_func_array('self::' . $name, $arguments);
		}
	}

	static private function owners()
	{
		$user = get_user();

		if (empty($user->admin)) {
			trhow_404();
		}
		
		$owners = DB::instance()->query("SELECT username as user, name, lastname, tel, email, observations as obs, active as status FROM users ")->result();
		
		use_view('owner-list', 'home', ['owners' => $owners, 'user' => $user]);
	}

	static private function owner($id)
	{
		$user = get_user();
		$db = DB::instance();
		$owner = $db->query("SELECT username as user, name, lastname, tel, email, observations as obs, active as status, watcher FROM users WHERE id = " . $id)->row();
		$owner->properties = $db->query("SELECT id, name, active FROM properties WHERE user_id = {$id}")->result();
		$properties = $db->query("SELECT id, name, active FROM properties WHERE user_id IS NULL")->result();

		save_form($owner);

		use_view('owner-form', 'home', ['owner' => $owner, 'properties' => $properties, 'user' => $user]);
	}

	static private function post_owner($id)
	{
		$user = get_user();
		$db = DB::instance();

		if (empty($user->admin)) {
			trhow_404();
		}

		try {
			$alias = [ 'User' => 'Usuario', 'Pass' => 'Contraseña', 'Name' => 'Nombre', 'Lastname' => 'Apellido', 'Tel' => 'Telefono', 'Obs' => 'Observaciones', 'Status' => 'Estado'];
			$rules = [ 'watcher' => 'boolean', 'props' => 'optional|array', 'props.*' => 'numeric', 'user' => 'required|slug|lengthBetween:8,16', 'pass' => 'slug|lengthBetween:8,16', 'name' => 'required', 'lastname' => 'required', 'tel' => 'required', 'email' => 'required|email', 'obs' => 'lengthMax:256', 'status' => 'required|in:1,0'];
			$data = (object) filter_input_post($rules, $alias);
		} catch (Exception $e) {
			handle_exception($e);
			save_form($_POST);
			redirect('admin/propietarios/' . $id);
		}

		try{
			$db->autocommit(false);

			$update = ["name = '{$data->name}'", "lastname = '{$data->lastname}'", "tel = '{$data->tel}'", "email = '{$data->email}'", "username = '{$data->user}'"];

			if(!empty($data->pass)){
				$update[] = "pass = MD5('{$data->pass}')";
			}

			if(!empty($data->obs)){
				$update[] = "observations = '{$data->obs}' ";
			}

			$update[] = "active = " . intval(!empty($data->status)); 
			$update[] = "watcher = " . intval(!empty($data->watcher));

			$db->query(sprintf("UPDATE users SET %s WHERE id = %d", implode(",", $update), $id));

			if(empty($data->props) || $data->watcher){
				$db->query("UPDATE properties SET user_id = NULL WHERE user_id = {$id}");
			}
			elseif(empty($data->watcher)){
				$props = implode(',', $data->props);
				$db->query(sprintf("UPDATE properties SET user_id = NULL WHERE user_id = %d AND id NOT IN(%s)", $id, $props));
				$db->query(sprintf("UPDATE properties SET user_id = %d WHERE id IN(%s)", $id, $props));
			}

			$db->commit();

			Session::getInstance()->msgs = ['Usuario actualizado.'];
		}
		catch (Exception $e) {
			$db->rollback();
			handle_exception($e);
		}

		redirect('admin/propietarios/' . $id);
	}

	static private function create_owner()
	{
		$user = get_user();

		if (empty($user->admin)) {
			trhow_404();
		}

		$properties = DB::instance()->query("SELECT id, name, active FROM properties WHERE user_id IS NULL")->result();
		
		use_view('owner-form', 'home', ['properties' => $properties, 'user' => $user]);
	}

	static private function post_create_owner()
	{
		$Session = Session::getInstance();
		$user = get_user();
		$db = DB::instance();

		if (empty($user->admin)) {
			trhow_404();
		}
		
		try {
			$alias = [ 'User' => 'Usuario', 'Pass' => 'Contraseña', 'Name' => 'Nombre', 'Lastname' => 'Apellido', 'Tel' => 'Telefono', 'Obs' => 'Observaciones', 'Status' => 'Estado'];
			$rules = [ 'props' => 'optional|array', 'props.*' => 'numeric', 'watcher' => 'boolean', 'user' => 'required|slug|lengthBetween:8,16', 'pass' => 'required|slug|lengthBetween:8,16', 'name' => 'required|alpha', 'lastname' => 'required|alpha', 'tel' => 'required', 'email' => 'required|email', 'obs' => 'lengthMax:256', 'status' => 'required|in:1,0'];
			$data = (object) filter_input_post($rules, $alias);
		} catch (Exception $e) {
			handle_exception($e);
			save_form($_POST);
			redirect('admin/propietarios/nuevo');
		}

		$watcher = intval(!empty($data->watcher));

		$id = $db->query("INSERT INTO users (name, lastname, tel, email, username, pass, observations, active, watcher) VALUES ('{$data->name}', '{$data->lastname}', '{$data->tel}', '{$data->email}', '{$data->user}', MD5('{$data->pass}'), '{$data->obs}', {$data->status}, {$watcher})")->id();

		if(!empty($data->props) && empty($watcher)){
			$props = implode(',', $data->props);
			$db->query(sprintf("UPDATE properties SET user_id = NULL WHERE user_id = %d AND id NOT IN(%s)", $id, $props));
			$db->query(sprintf("UPDATE properties SET user_id = %d WHERE id IN(%s)", $id, $props));
		}

		redirect('admin/propietarios/' . $id);
	}

	static private function regulations()
	{
		$user = get_user();
		$regulations = DB::instance()->query("SELECT * FROM regulations")->result();
		use_view('regulation-list', 'home', ['regulations' => $regulations, 'user' => $user]);
	}

	static private function edit_regulation($id)
	{
		$user = get_user();
		$regulation = DB::instance()->query(sprintf("SELECT name, description FROM regulations WHERE id = %s", $id))->row();

		use_view('regulation-form', 'home', ['regulation' => $regulation, 'user' => $user]);
	}

	static private function new_regulation()
	{
		use_view('regulation-form', 'home', ['regulation' => null, 'user' => get_user()]);
	}
	static private function create_regulation()
	{
		try {
			$user = get_user();

				$data = filter_input_post([ 'name' => 'required'], ['Name' => 'Nombre de archivo']);

				if (empty($_FILES['file']) || empty($_FILES['file']['name'])) {
					throw new VLException(json_encode(['Debe elejir un archivo pdf.']));
				}
				else{
					$upload = Upload::factory(__ROOT__ . '/regulations/');

					$upload->file($_FILES['file']);

					$upload->set_allowed_mime_types(array('application/pdf'));

					$results = $upload->upload(sprintf("%s.pdf", $data['name']));

					if (empty($results['status'])) {
						throw new VLException(json_encode(['Error con el archivo vuelva a intentarlo.']));
					}
					
					$data['src'] = $results['path'];
				}
				DB::instance()->query(sprintf("INSERT INTO regulations (%s) VALUES ('%s')", implode(',', array_keys($data)), implode("','", $data)));

				Session::getInstance()->msgs = ['Registro cargado.'];
		} catch (Exception $e) {
			print_r($e); exit;
			handle_exception($e);
		}

		redirect('admin/reglamentos');
	}

	static private function update_regulation($id)
	{
		try {
			$user = get_user();

			if(!isset($_POST['eliminar'])){
				$data = filter_input_post([ 'name' => 'required'], ['Name' => 'Nombre de archivo']);

				if (!empty($_FILES['file']) && !empty($_FILES['file']['name'])) {
					$upload = Upload::factory(__ROOT__ . '/regulations/');

					$upload->file($_FILES['file']);

					$upload->set_allowed_mime_types(array('application/pdf'));

					$results = $upload->upload(sprintf("%s.pdf", $data['name']));

					if (empty($results['status'])) {
						throw new VLException(json_encode(['Error con el archivo vuelva a intentarlo.']));
					}
					
					$data['src'] = $results['path'];

				}

				foreach ($data as $k => &$v) {
					$v = sprintf("%s = '%s'", $k, $v);
				}

				DB::instance()->query(sprintf("UPDATE regulations SET %s WHERE id = %s", implode(',', $data), $id));
				Session::getInstance()->msgs = ['Registro actualizado.'];
			}
			else{
				DB::instance()->query(sprintf("DELETE FROM regulations WHERE id = %s", $id));
				Session::getInstance()->msgs = ['Registro eliminado.'];
			}
		} catch (Exception $e) {
			handle_exception($e);
		}

		redirect('admin/reglamentos');
	}

	static private function props_info()
	{
		$db = DB::instance();
		$bookings = $db->query("SELECT B.id, DATE_FORMAT(date_in, '%d/%m/%Y') as date_in, DATE_FORMAT(date_out, '%d/%m/%Y') as date_out, occupant_name, occupant_tel, occupant_dni, occupant_email, observations FROM bookings AS B INNER JOIN bookings_properties ON booking_id = B.id GROUP BY B.id")->result();
		$props =  $db->query("SELECT BP.booking_id, P.name, P.observations FROM bookings_properties BP INNER JOIN bookings B ON B.id = booking_id INNER JOIN properties P ON P.id = property_id")->result();
		use_view('admin-props-booking-info', 'home', ['props' => $props, 'bookings' => $bookings, 'user' => get_user()]);
	}

	static private function lodge_info()
	{
		$bookings = DB::instance()->query("SELECT booking_id as id, guests,props, DATE_FORMAT(date_in, '%d/%m/%Y') as date, IF(time_shift, 'Noche', 'Mediodia') as time_shift, occupant_name as name, occupant_tel as tel, occupant_dni as dni, occupant_email as email, observations FROM bookings_lodge INNER JOIN bookings B ON B.id = booking_id ORDER BY date_booking DESC")->result();

		foreach ($bookings as &$booking) {
			$booking->guests = empty($booking->guests) ? [] : json_decode($booking->guests);
			$booking->props = $booking->props ? DB::instance()->query("SELECT name FROM properties WHERE id IN ({$booking->props})")->result() : [];
		}

		use_view('admin-lodge-booking-info', 'home', ['bookings' => $bookings, 'user' => get_user()]);
	}

	static private function booking_lodge_form()
	{
	}

	static private function booking_property_form()
	{
		$users = DB::instance()->query("SELECT id, CONCAT(name, ',', lastname) as name FROM users WHERE active = 1 AND admin = 0 AND watcher = 0")->result();
		$props = DB::instance()->query("SELECT P.* FROM users U INNER JOIN properties P ON P.user_id = U.id AND P.active = 1 WHERE U.active = 1 AND admin = 0 AND watcher = 0")->result();
		
		use_view('admin-props-booking-form', 'home', [ 'users' => $users, 'props' => $props, 'user' => get_user() ]);
	}
	
	static private function booking_property_post()
	{
		$Session = Session::getInstance();
		$alias = [ 'Name' => 'Nombre', 'Tel' => 'Telefono' ];
		$rules = [ 'user' => 'required|integer', 'props' => 'required|array', 'from' => 'required', 'to' => 'required', 'name' => 'required', 'tel' => 'required', 'dni' => 'integer', 'email' => 'email' ];
		$db = DB::instance();

		try {
			$post = (object) filter_input_post($rules, $alias);
			$user = get_user();
			$post->from = str_replace('/', '-', $post->from);
			$post->to = str_replace('/', '-', $post->to);
			$from = strtotime($post->from);
			$to = strtotime($post->to);
			$props = DB::instance()->query(sprintf("SELECT id, name FROM properties WHERE user_id = {$post->user} AND active = 1 AND id IN(%s)", implode(',', $post->props)))->result();

			if(array_column($props, 'id') !== $post->props){
				throw new Exception(json_encode(['Solo puede alquilar sus propiedades.']));
			}

			if($from >= $to){
				throw new Exception(json_encode(['Checkin debe ser anterior al Checkout.']));
			}

			$date_in = date('Y-m-d', $from);
			$date_out = date('Y-m-d', $to);
			$dni = empty($post->dni) ? null : $post->dni;
			$email = empty($post->email) ? null : $post->email;


			/**
			 *
			 * Reviso que las propiedades no tengan reservas entre los dias seleccionados
			 *
			 */
			$bookings = $db->query(sprintf("SELECT DISTINCT P.name FROM bookings_properties INNER JOIN bookings B ON B.id = booking_id INNER JOIN properties P ON P.id = property_id WHERE date_in < '$date_out' AND date_out > '$date_in' AND property_id IN (%s)", implode(',', $post->props)))->result();

			if (!empty($bookings)) {
				throw new Exception(json_encode([implode(', ', array_column($bookings, 'name')) . " no estan disponibles para las fechas seleccionadas." ]));
			}

			$db->autocommit(false);

			
			/**
			 *
			 * Grabo la reserva
			 *
			 */
			$booking_id = $db->query("INSERT INTO bookings (user_id, date_in, date_out, occupant_name, occupant_tel, occupant_dni, occupant_email) VALUES ({$post->user}, '{$date_in}', '{$date_out}', '{$post->name}', '{$post->tel}', '{$dni}', '{$email}')")->id();

			foreach ($props as $prop) {
				$db->query("INSERT INTO bookings_properties (property_id, booking_id) VALUES ({$prop->id}, {$booking_id})");
			}

			$db->commit();
			
			$Session->msgs = [ sprintf('¡Reservados: %s para el dia %s hasta el %s!', implode(', ', array_column($props, 'name')), date('d/m/Y', $from), date('d/m/Y', $to)) ];
			
		} 
		catch (Exception $e) {
			$Session->errors = json_decode($e->getMessage());
			
			save_form($_POST);
		}

		redirect('admin/propiedades');

	}
}