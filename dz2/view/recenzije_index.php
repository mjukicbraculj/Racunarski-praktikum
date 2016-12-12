<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<?php 
	echo "<h1>" . $naziv_proizvoda . "</h1>";
	foreach( $recenzijeLista as $recenzija){ ?>

		<div class="jedna_recenzija">
			<p> <?php echo " Autor: " . $recenzija->autor; ?> </p>
			<?php
				for($i=0; $i<$recenzija->ocjena; $i++)
					echo '<img src="star.png" alt="slika" class="zvjezdica" height="20" width="20">';
				for($i=$recenzija->ocjena; $i<5; $i++)
					echo '<img src="empty.png" alt="slika" class="zvjezdica" height="20" width="20">';
			?>
			<p> <?php echo htmlentities($recenzija->text); ?> </p>
		</div>

	<?php }
		$proizvod=$_GET['id'];
		if(!isset( $_SESSION['nova_r'] )){ 
			
			?>
			<form action=<?php echo '"' .  dirname($_SERVER['REQUEST_URI']) . "/amazon.php?rt=dodaj&id=" . $proizvod . '"';?>   method="POST">
				<button type="submit" name="nova_recenzija" value="<?php echo $recenzija->id;?>" $> Dodaj recenziju </button>
			</form>
		<?php
		} 
		if( isset( $_SESSION['greska']) ){
				echo "<p id='upozorenje'>" .  $_SESSION['greska'] . "</p>";
				unset($_SESSION['greska']);
		}
		else if( isset( $_SESSION['nova_r'] ) ){
				unset($_SESSION['nova_r']);		?>	
			<form action="<?php dirname($_SERVER['REQUEST_URI'])  . "/amazon.php?id=" .  $proizvod;?>" method="post">
				<label for="autor"> Autor: </label>
				<input type="text" name="autor" /></br></br>
				<input type="radio" name="ocjena" value="1"/>1</input>
				<input type="radio" name="ocjena" value="2"/>2</input>
				<input type="radio" name="ocjena" value="3"/>3</input>
				<input type="radio" name="ocjena" value="4"/>4</input>
				<input type="radio" name="ocjena" value="5"/>5</input></br></br>
				<textarea rows="10" cols="70" name="opis">Unesite text recenzije! </textarea></br></br>
				<input type="submit" name="dodaj_novu" value="Dodaj recenziju!"/>
			</form>
<?php
		}
	?>
		</br></br><a href=<?php echo '"' .  dirname($_SERVER['REQUEST_URI']) . "/amazon.php" . '"';?> >Svi proizvodi</a>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>