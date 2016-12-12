<?php

require_once 'zadatak2_izracunaj.php';
require_once 'zadatak2_DB.php';
class Klasa{
	private $id, $igrac1, $igrac2, $koef1, $koef2;

	function __construct($id, $igrac1, $igrac2, $koef1, $koef2){
		$this->id = $id;
		$this->igrac1 = $igrac1;
		$this->igrac2 = $igrac2;
		$this->koef1 = $koef1;
		$this->koef2 = $koef2;

	}

	function __toString(){
		//return $this->id . ' ' . $this->igrac1 . ' ' . $this->igrac2 . ' ' . $this->koef1 . ' ' .$this->koef2; 
		return  "<input type='radio' name='" . $this->id . "' value='". $this->koef1 . "'>" . $this->igrac1 . "</input>" .
 				"<input type='radio' name='" . $this->id . "' value='". $this->koef2 . "'/>" . $this->igrac2 . "</input></br>";
				
	} 

}


function xmlIspis(){

		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id, igrac1, igrac2, koef1, koef2 FROM zadatak2');
			$st->execute();
		}	
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() );}
		
		$arr = array();
	
		while( $row = $st->fetch() )
			$arr[] = new Klasa( $row['id'], $row['igrac1'], $row['igrac2'], $row['koef1'], $row['koef2'] );
		
		?>
		<form method="POST" action="zadatak2_izracunaj.php"> 
		<?php
		foreach( $arr as $i)
			echo $i . '</br>' ;?>
		<label for="iznos">Unesite ulozeni iznos:</label>
		<input type="text" name="iznos" /></br></br>
		<button type="submit" name="button" value="iznos">Izracunaj</button></br></br>
		
		</form>
<?php
}


xmlIspis();

?>

