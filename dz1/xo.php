<!DOCTYPE html>
<?php
	session_start();
	$_SESSION['greska'] = "";
	
	//funkcija provjerava jesu li imena igraca ispravna
	function validateName( $str ){
		if( !preg_match( '/^[a-zA-Z]{1,20}$/', $str ) ){
			$_SESSION['greska'] = "Ime smije sadržavati samo mala ili velika slova!";
			return false;
		}
		return true;
		
	}
	//klasa XO se brine o odvijanju igre
	class XO{
		private $ploca = array( "?", "?", "?", "?", "?", "?", "?", "?", "?" );
		private $igrac_na_potezu = "x";  //prvi igra o
		private $igraci = array();
	
		function __construct( $ime1, $ime2 ){
			$this->igraci["x"] = $ime1;
			$this->igraci["o"] = $ime2;
		}
	 
	    //funkcija vraca vrijednost polja ploca na indexu index
		public function plocaNaIndexu( $index ){
			return $this->ploca[$index];
		}
	
	    //vraća vrijednost igraca koji je sada na potezu
		function igracNaPotezu(){
			return $this->igrac_na_potezu;
		}
	
		//funkcija vraca vrijednost polja igraci na indexu index
		function igracNaIndexu( $index ){
				return $this->igraci[$index];
		}
	
		//funkcija provjerava je li odabrano polje na ploci
		//ako je odabrano prazno polje i ako nije kraj igre označava ga pripadnim slovom
		//ako je odabrano vec zauzeto polje onda ispisemo gresku
		function potez(){
			for( $i = 0; $i < 9; $i++ )
				if( isset( $_POST['button' . $i]) && !$this->jeKraj() ){
					if( $this->ploca[$i] !== "o" && $this->ploca[$i] !== "x" ){
						$this->ploca[$i] = $this->igrac_na_potezu;
						$this->ima_li_pobjednik();
						$this->promijeni_igraca();
					}
					else 
						$_SESSION['greska'] = "Odabrali ste iskoristeno polje!";
				}	
		}
	
		//funkcija se brine o mijenjanju redoslijeda igrača
		private function promijeni_igraca(){
			if( $this->igrac_na_potezu === "o" )
				$this->igrac_na_potezu = "x";
			else
				$this->igrac_na_potezu = "o";
		}
		
		//funkcija provjerava imamo li pobjdnika u recima ploce
		private function uRetku(){
			for( $i = 0; $i < 3; $i++)
				if($this->ploca[$i*3] === $this->ploca[$i*3+1] && 
					$this->ploca[$i*3] === $this->ploca[$i*3+2] && $this->ploca[$i*3] !== "?"){
					$_SESSION['pobjednik'] = $this->igrac_na_potezu;
					$_SESSION['bojanje'] = array( $i*3, $i*3+1, $i*3+2 );
				}
		}
	
	    //funkcija provjerava imamo li pobjednika u stupcima ploce
		private function uStupcu(){
			for( $i = 0; $i < 3; $i++)
				if($this->ploca[$i] === $this->ploca[$i+3] &&$this->ploca[$i] === $this->ploca[$i+6] && $this->ploca[$i] !== "?"){
					$_SESSION['pobjednik'] = $this->igrac_na_potezu;
					$_SESSION['bojanje'] = array( $i, $i+3, $i+6 ); 
				}
		}
	
	   //funkcija provjerava imamo li pobjednika na dijagonali ploce
		private function naDijagonali(){
			if( $this->ploca[0] === $this->ploca[4] && $this->ploca[0] === $this->ploca[8] && $this->ploca[0] !== "?" ){
				$_SESSION['pobjednik'] = $this->igrac_na_potezu;
				$_SESSION['bojanje'] = array( 0, 4, 8 );
				return;
			}
			if( $this->ploca[2] === $this->ploca[4] && $this->ploca[2] === $this->ploca[6] && $this->ploca[2] !== "?" ){
				$_SESSION['pobjednik'] = $this->igrac_na_potezu;
				$_SESSION['bojanje'] = array( 2, 4, 6 );
				return;
			}
		}
	  //funkcija provjerava imamo li pobjednika
		private function ima_li_pobjednik(){
			$this->uRetku();
			$this->uStupcu();
			$this->naDijagonali();
		}
	
	   //funkcija provjerava je li kraj igre
		function jeKraj(){
			for( $i = 0; $i < 9; $i++ )
				if( $this->ploca[$i] !== "o" && $this->ploca[$i] !== "x" )
					return false;
			return true;
		}
};

?>
	<html>
			<head>
				<style> 
						body {text-align: center;}
						table{ margin-left: auto; margin-right:auto; }	
						.obojani_button { background-color: magenta; } 
						.greska {color: red; } 
						table input{ height: 70px; width: 70px; font-size: 30px;}
						
				</style>
				<meta charset="UTF-8"/>
			</head>
	
	<?php
	//ako je odabran button reset onda izlazimo iz igre
	if( isset( $_POST['reset'] ) ){
		session_unset();
		session_destroy();
	}
	
	
	//prvi igrac je x, drugi o, ako su imena ispravna konstruiramo igru XO
	if( isset( $_POST['prvi_igrac']) && isset( $_POST['drugi_igrac'] ) && validateName( $_POST['prvi_igrac'] ) && validateName( $_POST['drugi_igrac'])  ) 
		$_SESSION['igra'] = new XO( $_POST['prvi_igrac'], $_POST['drugi_igrac'] );
		
		
	//ako postoji igra XO i odabrano je neko polje ploče izvršimo odabrani potez
	if( isset( $_SESSION['igra'] ) ){
		for( $k = 0; $k < 9; $k++ ){
			if( isset( $_POST['button' . $k] ) && !isset($_SESSION['pobjednik'] ))
				$_SESSION['igra']->potez();
				$nova_igra = $_SESSION['igra'];
		}
	?>		
			<body>
				<h1>Dobrodošli u igru XO!</h1>
				<table> 
					<?php for( $i = 0; $i < 3; $i++ ){?>
					<tr>
						<?php for( $j = 0; $j < 3; $j++ ) {?>
						<td>
							<form action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>" method="POST">
								<input type="hidden" name="<?php echo "button" . ($i*3+$j); ?>"/>
								<input type="submit" value="<?php echo $nova_igra->plocaNaIndexu($i*3+$j); ?>"
									class = "<?php if( isset( $_SESSION['bojanje'] ) ) 
														for( $k = 0; $k < 3; $k++ )
															if( $_SESSION['bojanje'][$k] == ($i*3+$j))
																echo "obojani_button"; ?>"/>
							</form>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</table>
			<?php if( $_SESSION['greska'] !== "" ){?>
			<p  class="greska" >
					<?php echo $_SESSION['greska']; ?>
			</p>
			<?php } ?>
			<p>
				<?php
				if( !isset( $_SESSION['pobjednik'] )){
					if( !$nova_igra->jeKraj() )
						echo "Na potezu je " . $nova_igra->igracNaIndexu($nova_igra->igracNaPotezu()) .
						 " ( Igrac " . $nova_igra->igracNaPotezu() . " )!"; 
				}
				else echo "Pobjednik je " .  $nova_igra->igracNaIndexu($_SESSION['pobjednik'] ) . "!". "<br/>" ;
				if( $nova_igra->jeKraj())
					echo "Kraj igre!";
				?>
			 </p>
			<form action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>" method="POST">
					<input type="hidden" name="reset"/>
					<input type="submit" value="Restartaj igru!"/>
			</form>
			</body>
<?php
	}
	
	//inace korisniku ispisemo pocetni ekran
	else{
		?>

		<body>
			<h1>Dobrodošli u igru XO!</h1>
			<form action="<?php echo htmlentities( $_SERVER['PHP_SELF']); ?>", method="POST">
				Unesite ime igrača x: <input type="text" name="prvi_igrac"/> <br/><br/>
				Unesite ime igrača 0: <input type="text" name="drugi_igrac"/> <br/></br>
				<input type="submit" value="Zapocni igru!"/>
			</form>
		<p class="greska">
			<?php
				if( isset( $_SESSION['greska'] ) )
					echo $_SESSION['greska'];
			?>
		</p>
		</body>
	<?php
	}
	?>
	</html>
