<?php 

class Props
{
	public static function __callStatic($name, $arguments)
    {
		$user = get_user();

		if(empty($user->watcher)){
			if(!empty($user->admin)){
				_global('__NAVBAR__', 'admin');
			}

    		call_user_func_array('self::' . $name, $arguments);
		}
		else{
			trhow_404();
		}
    }


	static private function index()
	{
		$user = get_user();
		$props = DB::instance()->query("SELECT id, name FROM properties WHERE user_id = {$user->id} AND active = 1")->result();

		use_view('props-booking-form', 'home', ['props' => $props, 'user' => $user]);
	}

	static private function delete()
	{
		if(!empty($_POST['delete'])){
			$user = get_user();
			$db = DB::instance();

			$db->query(sprintf("DELETE FROM bookings WHERE id='%s' AND user_id = %s", $_POST['delete'], $user->id));
			
			$Session = Session::getInstance();

			if($db->count()){
				$Session->errors = json_encode(['La reserva no existe o no tiene permiso para eliminarla.']);
			}
			else{
				$Session->msgs = [ "Reserva eliminada." ];
			}
		}

		redirect('propiedades/info');
	}

	static private function booking()
	{
		$Session = Session::getInstance();
		$alias = [ 'Name' => 'Nombre', 'Tel' => 'Telefono', 'Props' => 'Propiedades', 'From' => 'Fecha desde', 'To' => 'Fecha hasta' ];
		$rules = [ 'props' => 'required|array', 'from' => 'required', 'to' => 'required', 'name' => 'required', 'tel' => 'required', 'dni' => 'integer', 'email' => 'email', 'observations' => 'lengthMax:50'];
		$db = DB::instance();

		try {
			$post = (object) filter_input_post($rules, $alias);
			$user = get_user();
			$post->from = str_replace('/', '-', $post->from);
			$post->to = str_replace('/', '-', $post->to);
			$from = strtotime($post->from);
			$to = strtotime($post->to);
			$props = $db->query(sprintf("SELECT id, name FROM properties WHERE user_id = {$user->id} AND active = 1 AND id IN(%s)", implode(',', $post->props)))->result();

			if(count($props) !== count($post->props)){
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
			$booking_id = $db->query("INSERT INTO bookings (user_id, date_in, date_out, occupant_name, occupant_tel, occupant_dni, occupant_email, observations) VALUES ({$user->id}, '{$date_in}', '{$date_out}', '{$post->name}', '{$post->tel}', '{$dni}', '{$email}', '{$post->observations}')")->id();

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

		redirect('propiedades');
	}

	static private function info()
	{
		$db = DB::instance();
		$user = get_user();
		$bookings = $db->query("SELECT B.id, DATE_FORMAT(date_in, '%d/%m/%Y') as date_in, DATE_FORMAT(date_out, '%d/%m/%Y') as date_out, occupant_name, occupant_tel, occupant_dni, occupant_email, observations FROM bookings AS B INNER JOIN bookings_properties ON booking_id = B.id WHERE user_id = {$user->id} GROUP BY B.id")->result();
		$props =  $db->query("SELECT BP.booking_id, P.name, P.observations FROM bookings_properties BP INNER JOIN bookings B ON B.id = booking_id INNER JOIN properties P ON P.id = property_id WHERE B.user_id = {$user->id}")->result();

		use_view('props-booking-info', 'home', ['props' => $props,'bookings' => $bookings, 'user' => $user]);
	}
}