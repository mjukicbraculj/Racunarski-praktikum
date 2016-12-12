<?php


class dodajController extends BaseController{
	
	///funkcja otvara korisniku prozor za unos nove recenzije
	///ali samo ako je korisnik ulogiran
	public function amazon(){
			$this->registry->template->title = 'Welcome to our web-shop!';
		if( !isset($_SESSION['username'])){
			$_SESSION['greska'] = 'Prvo se morate ulogirati!';
			header('Location: ' . dirname( $_SERVER['REQUEST_URI']) . "/amazon.php?id=" . $_GET['id'] );
		}
		else{
			$_SESSION['nova_r'] = 'da';		
			header('Location: ' . dirname( $_SERVER['REQUEST_URI']) . "/amazon.php?id=" . $_GET['id']);
		}
			
	}
}