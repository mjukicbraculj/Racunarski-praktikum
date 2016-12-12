<?php
session_start();

function vrati_boju($boja){
	
	if($boja === 'Y')
		return 'yellow';
	if($boja === 'R')
		return 'red';
	if($boja === 'B')
		return 'blue';
	if($boja === 'G')
		return 'green';

}

function vise_boja($rijec,$boja){
	if(strlen($rijec)===strlen($boja))
		return 1;
	return 0;
}
	
if(!isset($_SESSION['brojac']))
	$_SESSION['brojac'] = 0;

function validate_boja($boja){
	return preg_match('/^[BRGY]{1,}$/', $boja);
}
	
function validate_rijec($rijec){
	return preg_match('/^[a-zA-Z]{1,}$/', $rijec);
}

function jedna_boja($boja){
	if(strlen($boja)===1)
		return 1;
	return 0;
}


if( isset($_POST['rijec']) && isset($_POST['boja']) && $_POST['rijec']!=='KRAJ'){
	
	if( !isset($_SESSION['prica'])){
		$_SESSION['prica'] =  array();
	}
	if( validate_boja($_POST['boja']) && validate_rijec($_POST['rijec'])){
		
		if(jedna_boja($_POST['boja'])){
			$_SESSION['prica'][$_POST['rijec']] = $_POST['boja'];

		}
		else if(vise_boja($_POST['rijec'], $_POST['boja'])){
			echo "tu sam";
			for($i=0; $i<strlen($_POST['rijec']); $i++)
				$_SESSION['prica'][$_POST['rijec'][$i]] = $_POST['boja'][$i];
		
		}	
	}
	
	
}
if(isset($_POST['rijec']) && $_POST['rijec']==='KRAJ'){
	
	$_SESSION['naslov']=$_POST['naslov'];
}

?>



<!DOCTYPE html>
<html>
<head>
	<title>ZAD1</title>
	<meta charset="UTF-8" />
</head>
<body>
	<?php
		if(isset($_SESSION['naslov']) && $_SESSION['naslov']!=='')
			echo "<h1>" . $_SESSION['naslov']. "</h1>";
		if(isset($_SESSION['prica']))
			foreach($_SESSION['prica'] as $key=>$value ){
				$boja = vrati_boju($value);
				echo "<span style='background-color:" . $boja ."'>" . $key . "</span> ";
				}?>

	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

			<label for="rijec">Unesite novu rijec</label>
			<input type="text" name="rijec" /></br></br>

			<label for="boja">Unsite boju nove rijeci </label>
			<input type="text" name="boja" /></br></br>
			<button type="submit" name="button" value="nova">Dodaj novu rijec</button></br></br>
			
		<?php
		if(isset($_SESSION['naslov'])){?>
			<label for="naslov">Unesite naslov</label>
			<input type="text" name="naslov" /></br></br>
			<button type="submit" name="button" value="naslov">Dodaj naslov</button></br></br>
		<?php }?>
			
			
	</form>

</body>
</html>
