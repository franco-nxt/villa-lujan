<?php 

class Watcher
{
	public static function __callStatic($name, $arguments)
	{
		$user = get_user();

		if(empty($user->watcher)){
			trhow_404();
		}
		else{
			_global('__NAVBAR__', strtolower(__CLASS__));
			call_user_func_array('self::' . $name, $arguments);
		}
	}

	static private function lodge()
	{
		$db = DB::instance();
		$bookings = $db->query("SELECT *, DATE_FORMAT(date_in, '%d/%m/%Y') as date, IF(time_shift, 'tarde', 'mañana') as time_shift FROM bookings_lodge INNER JOIN bookings B ON B.id = booking_id WHERE date_in >= CURRENT_DATE ORDER BY date_in ASC")->result();

		foreach ($bookings as &$booking) {
			$booking->props = empty($booking->props) ? [] : $db->query("SELECT name FROM properties WHERE id IN " . sprintf("(%s)", $booking->props))->result_array();
		}

		use_view('watcher-lodge-info', 'home', ['bookings' => $bookings, 'user' => get_user()]);
	}

	static private function bookings()
	{
		$user = get_user();
		$db = DB::instance();
		$bookings = $db->query("SELECT B.id,occupant_name,occupant_tel,occupant_dni,occupant_email,observations, DATE_FORMAT(date_in, '%d/%m/%Y') as date_in, DATE_FORMAT(date_out, '%d/%m/%Y') as date_out FROM bookings_properties INNER JOIN bookings B ON B.id = booking_id WHERE date_out >= CURRENT_DATE GROUP BY booking_id ORDER BY date_in ASC ")->result();
		$props =  $db->query("SELECT BP.booking_id, P.name, P.observations FROM bookings_properties BP INNER JOIN bookings B ON B.id = booking_id INNER JOIN properties P ON P.id = property_id ")->result();
		
		use_view('watcher-properties-info', 'home', ['bookings' => $bookings, 'props' => $props, 'user' => get_user()]);
	}
}