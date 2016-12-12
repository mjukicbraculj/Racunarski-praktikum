<?php

include_once 'db.class.php';

class RecenzijeService{

	function getAllRecenzije( $id ){
	
		try{
			$db = DB::getConnection();
			$st = $db->prepare( "SELECT id, id_proizvoda, autor, ocjena, tekst FROM recenzije WHERE id_proizvoda='" . $id . "'");
			$st->execute();
		}	
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() );}
		
		$arr = array();
	
		while( $row = $st->fetch() ){
			
			$arr[] = new Recenzija( $row['id'], $row['id_proizvoda'], $row['autor'], $row['ocjena'], $row['tekst'] );
		
		}
		
		return $arr;
	}

	
}
?>
