<?php


class RecenzijeController extends BaseController{
	
	public function amazon(){

		$db = DB::getConnection();

		if(isset($_POST['dodaj_novu'])){

			//onda u bazu moramo dodati novu recenziju
			try
			{
				$st = $db->prepare( 'INSERT INTO recenzije( id_proizvoda, autor, ocjena, tekst) VALUES ( :id_proizvoda, :autor, :ocjena, :tekst)' );

				$st->execute( array( 'id_proizvoda' => $_GET['id'], 'autor' => $_POST['autor'], 'ocjena' => $_POST['ocjena'],  'tekst' => htmlentities($_POST['opis'] ) ));
			}
			catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }
			unset($_POST['dodaj_novu']);
		}
		
		try{
			$db = DB::getConnection();
			$sr = $db->prepare( "SELECT naziv FROM proizvodi WHERE id='" . $_GET['id'] . "'");
			$sr->execute();
		}	
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() );}
		$row = $sr->fetch();

		$this->registry->template->naziv_proizvoda = $row['naziv'];

		
		$ps = new recenzijeService();
		
		$this->registry->template->title = 'Welcome to our web-shop!';
		$this->registry->template->recenzijeLista = $ps->getAllRecenzije( $_GET['id']);
		
		$this->registry->template->show( 'recenzije_index' );
		
	}
	
	
}