<?php
require_once 'zadatak2_DB.php';

if(isset($_POST['button'])){
	//echo $_POST['iznos'];
			$zarada=$_POST['iznos'];
			//echo "racunam</br>";
			try{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT id, igrac1, igrac2, koef1, koef2 FROM zadatak2');
				$st->execute();
			}	
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() );}
			
			$arr = array();
		
			while( $row = $st->fetch() ){
				if(isset($_POST[$row['id']])){
					//echo "nasao jednoog </br>";
					if($_POST[$row['id']] === $row['koef1'] ){
						echo $row['igrac1'] . ' ' . $row['igrac2'] . "</br>";
						$zarada *= $row['koef1'];
					}
					else if($_POST[$row['id']] === $row['koef2']){
						echo $row['igrac1'] . ' ' . $row['igrac2'] .  "</br>";
						$zarada *= $row['koef2'];
					}
				}
			}
			echo "Zarada je: " . $zarada . "</br>";
				
				
}?>
