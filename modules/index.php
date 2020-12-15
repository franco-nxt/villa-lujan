<?php 

class Index
{
	static public function login()
	{
		$Session = Session::getInstance();
		$post = (object) filter_input_post(['action' => 'optional']);

		if(!empty($post->action) && $post->action == 'INGRESAR'){
			
			$Session->reset();

			try {
				$username = filter_input(INPUT_POST, 'user');
				$pass = filter_input(INPUT_POST, 'password');

				if(empty($username) || empty($pass)){
					throw new Exception('Usuario o contraseña invalidos.');
				}
				else{
					$db = DB::instance();
					$q = sprintf("SELECT id, name, lastname, %d AS deadline, admin, watcher FROM users WHERE username = '%s' AND pass = '%s' AND active = 1", time() + SESSION_TIME, $username, md5($pass));

					$db->query($q);

					if(empty($db->count())){
						throw new Exception('Usuario o contraseña invalidos.');
					}

					$Session->USER = $db->row();
					
					redirect();
				}
			} catch (Exception $e) {
				$Session->errors = [$e->getMessage()];
				redirect_login();
			}
		}
		else{
			redirect_login();
		}
	}

	static public function main()
	{
		$Session = Session::getInstance();

		if(empty($Session->USER)){
			use_view('login', 'login');
		}
		else{
			$user = get_user();

			if (empty($user->watcher)) {
				Lodge::index();
			}
			else{
				Watcher::lodge();				
			}
		}
	}

	static public function logout()
	{
		Session::getInstance()->reset();
		redirect();
	}

}