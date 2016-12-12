<?php


class userController extends BaseController{
	
	///funkcja otvara korisniku prozor za unos nove recenzije
	///ali samo ako je korisnik ulogiran
	public function amazon(){
		$this->registry->template->title = 'Welcome to our web-shop!';
		if(isset($_POST['gumb']) && $_POST['gumb']==='login')
			$this->registry->template->show( 'login' );
		else if(isset($_POST['gumb']) && $_POST['gumb']==='register')
			$this->registry->template->show( 'register' );
		else{
			$this->registry->template->greska="nije ni login ni register!";
			$this->registry->template->show( 'greska' );
		}
	}

	public function login(){
		$this->registry->template->title = 'Welcome to our web-shop!';
		if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) )
		{
			$this->registry->template->greska = 'Trebate unijeti korisničko ime, lozinku.';
			$this->registry->template->show( 'greska' );
		}

		if( !preg_match( '/^[a-zA-Z]{3,10}$/', $_POST['username'] ) )
		{
			$this->registry->template->greska = 'Korisničko ime treba imati između 3 i 10 slova.';
			$this->registry->template->show( 'greska' );
		}

		// Dakle dobro je korisničko ime. 
		// Provjeri taj korisnik postoji u bazi; dohvati njegove ostale podatke.
		$db = DB::getConnection();

		try
		{
			$st = $db->prepare( 'SELECT username, password, has_registered FROM UserList WHERE username=:username' );
			$st->execute( array( 'username' => $_POST['username'] ) );
		}
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

		$row = $st->fetch();

		if( $row === false )
		{
			$this->registry->template->greska = 'Korisnik s tim imenom ne postoji.';
			$this->registry->template->show( 'greska' );
		}
		else if( $row['has_registered'] === '0' )
		{
			$this->registry->template->greska = 'Korisnik s tim imenom se nije još registrirao. Provjerite e-mail.';
			$this->registry->template->show( 'greska' );
		}
		else if( !password_verify( $_POST['password'], $row['password'] ) )
		{
			$this->registry->template->greska = 'Lozinka nije ispravna.' ;
			$this->registry->template->show( 'greska' );
		}
		else
		{
			// Sad je valjda sve OK. Ulogiraj ga.
			$_SESSION['username'] = $_POST['username'];
			header('Location: '  . dirname(dirname($_SERVER['REQUEST_URI']) ) . "/amazon.php" );
			exit();
		}
}

	public function register(){
		$this->registry->template->title = 'Welcome to our web-shop!';

		if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) )
		{
			$this->registry->template->greska = 'Trebate unijeti korisničko ime, lozinku i e-mail adresu';
			$this->registry->template->show( 'greska' );
		}

		if( !preg_match( '/^[A-Za-z]{3,10}$/', $_POST['username'] ) )
		{
			$this->registry->template->greska = 'Korisničko ime treba imati između 3 i 10 slova.';
			$this->registry->template->show( 'greska' );
		}
		else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
		{
			$this->registry->template->greska = 'E-mail adresa nije ispravna..';
			$this->registry->template->show( 'greska' );
		}
		else
		{
			// Provjeri jel već postoji taj korisnik u bazi
			$db = DB::getConnection();

			try
			{
				$st = $db->prepare( 'SELECT * FROM UserList WHERE username=:username' );
				$st->execute( array( 'username' => $_POST['username'] ) );
			}
			catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

			if( $st->rowCount() !== 0 )
			{
				// Taj user u bazi već postoji
				$this->registry->template->greska = 'Korisnik s tim imenom već postoji u bazi.';
				$this->registry->template->show( 'greska' );
			}

			// Dakle sad je napokon sve ok.
			// Dodaj novog korisnika u bazu. Prvo mu generiraj random string od 10 znakova za registracijski link.
			$reg_seq = '';
			for( $i = 0; $i < 20; ++$i )
				$reg_seq .= chr( rand(0, 25) + ord( 'a' ) ); // Zalijepi slučajno odabrano slovo

			try
			{
				$st = $db->prepare( 'INSERT INTO UserList(username, password, email, reg_seq, has_registered) VALUES ' .
					                '(:username, :password, :email, :reg_seq, 0)' );
				
				$st->execute( array( 'username' => $_POST['username'], 
					                 'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
					                 'email' => $_POST['email'], 
					                 'reg_seq'  => $reg_seq ) );
			}
			catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

			// Sad mu još pošalji mail
			$to       = $_POST['email'];
			$subject  = 'Registracijski mail';
			$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
			$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/amazon.php?rt=user/register_accept&niz=' . $reg_seq . "\n";
			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
			            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
			            'X-Mailer: PHP/' . phpversion();

			$isOK = mail($to, $subject, $message, $headers);

			if( !$isOK ){
				$this->registry->template->greska = 'Greška: ne mogu poslati mail.';
				$this->registry->template->show( 'greska' );
			}

			// Zahvali mu na prijavi.
			$this->registry->template->title = 'Welcome to our web-shop!';
			$this->registry->template->show( 'zahvala' );
			
		}

	}



	public function logout(){

			session_unset();
			session_destroy();
			header('Location: '  . dirname(dirname($_SERVER['REQUEST_URI']) ) . "/amazon.php" );
			exit();

	}

	public function register_accept(){

		if( !isset( $_GET['niz'] ) || !preg_match( '/^[a-z]{20}$/', $_GET['niz'] ) )
			exit( 'Nešto ne valja s nizom.' );

		// Nađi korisnika s tim nizom u bazi
		$db = DB::getConnection();

		try
		{
			$st = $db->prepare( 'SELECT * FROM UserList WHERE reg_seq=:reg_seq' );
			$st->execute( array( 'reg_seq' => $_GET['niz'] ) );
		}
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

		$row = $st->fetch();

		if( $st->rowCount() !== 1 )
			exit( 'Taj registracijski niz ima ' . $st->rowCount() . 'korisnika, a treba biti točno 1 takav.' );
		else
		{
			// Sad znamo da je točno jedan takav. Postavi mu has_registered na 1.
			try
			{
				$st = $db->prepare( 'UPDATE UserList SET has_registered=1 WHERE reg_seq=:reg_seq' );
				$st->execute( array( 'reg_seq' => $_GET['niz'] ) );
			}
			catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

			// Sve je uspjelo, zahvali mu na registraciji.
			$this->registry->template->title = 'Welcome to our web-shop!';
			$this->registry->template->show( 'nova_zahvala' );
			
		}
	}
}

?>