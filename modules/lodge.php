<?php 

class Lodge
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
		$dates = DB::instance()->query("SELECT DATE_FORMAT(date_in, '%b %d, %Y') as date, BL.time_shift FROM bookings B INNER JOIN bookings_lodge BL ON BL.booking_id = B.id WHERE date_in >= CURRENT_DATE")->result();

		use_view('lodge-booking-form', 'home', ['dates' => $dates, 'props' => $props, 'user' => get_user()]);
	}


	static private function booking()
	{
		$Session = Session::getInstance();
		$alias = [ 'Calendar' => 'El dia', 'Name' => 'Nombre', 'Time' => 'La fecha', 'Guests' => 'Invitados'];
		$rules = [ 'calendar' => 'required|integer', 'name' => 'required', 'time' => 'required|in:0,1', 'guests' => 'required|array', 'props' => 'optional|array' ];

		$db = DB::instance();
		
		try {
			$post = (object) filter_input_post($rules, $alias);
			$user = get_user();
			$date_in = $date_out = date('Y-m-d', $post->calendar / 1000);
			
			if((strtotime($date_in) - strtotime(date('Y-m-d'))) /3600 < 24){
				throw new Exception(json_encode(['Solo se puede reservar el quincho hasta 24hs antes.']));
			}

			if (count($post->guests) < 5) {
				throw new Exception(json_encode(['No se puede reservar el quincho para menos de 5 invitados.']));
			}

			if (count($post->guests) > 13) {
				throw new Exception(json_encode(['No se puede reservar el quincho para más de 13 invitados.']));
			}

			foreach ($post->guests as $guest) {
				if (empty($guest)) {
					throw new Exception(json_encode(['El nombre de todos los invitados es obligatorio.']));
				}
			}

			/**
			 *
			 * Busco si hay reservas pendientes
			 *
			 */
			$res = $db->query("SELECT B.date_in FROM bookings B INNER JOIN bookings_lodge BL ON BL.booking_id = B.id WHERE B.user_id = {$user->id} AND date_in >= CURRENT_DATE")->row();	

			if(!empty($res->date_in)){
				throw new Exception(json_encode([sprintf('No puede reservar porque usted tiene una reserva pendiente para el dia %s.', date('d/m/Y', strtotime($res->date_in)))]));
			}

			/**
			 *
			 * Reviso si la fecha seleccionada esta libre
			 *
			 */
			$count = $db->query("SELECT COUNT(B.id) AS value FROM bookings B INNER JOIN bookings_lodge BL ON BL.booking_id = B.id WHERE BL.time_shift = {$post->time} AND B.date_in = '{$date_in}'")->row();	

			if(!empty($count->value)){
				throw new Exception(json_encode(['El turno no esta disponible']));
			}


		} catch (Exception $e) {
			$Session->errors = json_decode($e->getMessage());
			
			save_form($_POST);
			redirect('quincho');
		}


		/**
		 *
		 * Grabo el turno
		 *
		 */
		try{
			$db->autocommit(false);
			
			$guests = empty($post->guests) ? '[]' : json_encode($post->guests);
			$props = implode(',', $post->props);
			$booking_id = $db->query("INSERT INTO bookings (user_id, date_in, date_out, occupant_name, occupant_tel, occupant_dni, occupant_email) VALUES ({$user->id}, '{$date_in}', '{$date_out}', '{$post->name}', 0, 0, '')")->id();
			$db->query("INSERT INTO bookings_lodge (booking_id, time_shift, guests, props) VALUES ({$booking_id}, {$post->time}, '{$guests}', '{$props}')");

			$db->commit();
			
			$Session->msgs = [ sprintf('¡Reserva generada para el dia %s turno %s!', date('d/m/Y', $post->calendar / 1000), boolval($post->time) ? 'de la noche' : 'del mediodía' ) ];
			
			redirect('quincho');
		}
		catch (Exception $e) {
			$db->rollback();
			
			$Session->errors = ['No se pudo generar la reserva.'];	
			
			save_form($_POST);
			redirect('quincho');

			throw $e;
		}
	}

	static private function info()
	{
		$db = DB::instance();
		$user = get_user();
		$booking = $db->query("SELECT props, booking_id as id, time_shift as time, guests, DATE_FORMAT(date_in, '%d/%m/%Y') as date, occupant_name as name, occupant_tel as tel, occupant_dni as dni, occupant_email as email, observations FROM bookings_lodge INNER JOIN bookings B ON B.id = booking_id AND user_id = {$user->id} WHERE date_in >= CURRENT_DATE ORDER BY date_booking DESC LIMIT 1")->row();
		$guests = empty($booking->guests) ? [] : json_decode($booking->guests);
		$props = empty($booking->props) ? [] : $db->query("SELECT name FROM properties WHERE id IN " . sprintf("(%s)", $booking->props))->result();

		use_view('lodge-booking-info', 'home', ['booking' => $booking, 'guests' => $guests, 'props' => $props, 'user' => $user]);
	}

	static private function update_info()
	{
		$Session = Session::getInstance();
		$db = DB::instance();
		$alias = [ 'Name' => 'Nombre', 'Tel' => 'Telefono' ];
		$rules = [ 'name' => 'required', 'booking' => 'required|integer', 'guests' => 'optional|array' ];
		
		try {
			$user = get_user();
			
			if(empty($_POST['delete'])){

				$post = (object) filter_input_post($rules, $alias);

				if (count($post->guests) < 5) {
					throw new Exception(json_encode(['No se puede reservar el quincho para menos de 5 invitados.']));
				}
	
				if (count($post->guests) > 13) {
					throw new Exception(json_encode(['No se puede reservar el quincho para más de 13 invitados.']));
				}

				$db->query("UPDATE bookings SET occupant_name = '{$post->name}', occupant_tel = '{$post->tel}', occupant_dni = '{$post->dni}', occupant_email = '{$post->email}' WHERE id = {$post->booking}");
				$db->query(sprintf("UPDATE bookings_lodge SET guests = '%s' WHERE booking_id = {$post->booking}", json_encode($post->guests)));
			}
			else{
				$db->query(sprintf("DELETE FROM bookings WHERE id='%s' AND user_id = %s", $_POST['delete'], $user->id));
				
				$Session = Session::getInstance();

				if($db->count()){
					throw new Exception(json_encode(['La reserva no existe o no tiene permiso para eliminarla.']));
				}
				else{
					$Session->msgs = [ "Reserva eliminada." ];
				}
			}

	
		} catch (Exception $e) {
			$Session->errors = json_decode($e->getMessage());
			
			save_form($_POST);
		}

		redirect('quincho/info');
	}
}