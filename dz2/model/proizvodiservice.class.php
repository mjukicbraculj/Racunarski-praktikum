<?php

include_once 'db.class.php';

class ProizvodiService{

	function getAllProizvodi(){
	
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id, naziv, opis, cijena FROM proizvodi ORDER BY id');
			$st->execute();
		}	
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() );}
		
		$arr = array();
	
		while( $row = $st->fetch() ){
			
			$arr[] = new Proizvod( $row['id'], $row['naziv'], $row['opis'], $row['cijena'] );
		
		}
		
		//za svaki proizvod racunamo njegovu prosjecnu ocjenu
		foreach( $arr as $proizvod ){
			$brojac = 0; 
			$suma = 0;
			
			try{
				$db = DB::getConnection();
				$st = $db->prepare( "SELECT ocjena FROM recenzije WHERE id_proizvoda='" . $proizvod->id . "'" );
				$st->execute();
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			
			while( $row = $st->fetch() ){
				
				$brojac++;
				$suma = $suma + $row['ocjena'];
				
			}
			if($brojac === 0){
				$proizvod->prosjecna_ocjena = 0;
				$proizvod->broj_recenzija = 0;
			}
			else{
				$proizvod->prosjecna_ocjena = $suma/$brojac;
				$proizvod->broj_recenzija = $brojac;
			}
				
		}
		
		return $arr;
	}

	
}
?>
