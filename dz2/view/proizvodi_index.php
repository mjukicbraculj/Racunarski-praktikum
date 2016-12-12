<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<?php 
	unset($_SESSION['greska']);
	foreach( $proizvodiLista as $proizvod){ ?>
		<div class="proizvod">
		<h2>
			<?php echo $proizvod->id . '.  ' . $proizvod->naziv;?>
		</h2>

		<p class="cijena"> <?php echo '(' . $proizvod->cijena . 'kn)'; ?> </p></br>

		<?php 
		//echo "<p>" . $proizvod->prosjecna_ocjena . "</p>";
		//echo "<p>" . $proizvod->broj_recenzija . "</p>";

		$floor = floor($proizvod->prosjecna_ocjena);
		$pomak = 0;

		for( $i = 0; $i < $floor; $i++ )
			echo '<img src="star.png" alt="slika" class="zvjezdica" height="20" width="20">';

		if( $proizvod->prosjecna_ocjena - $floor >= 0.5 ){
			$pomak = 1;
			echo '<img src="half.png" alt="slika" class="zvjezdica" height="20" width="20">';
		}

		for( $i = $floor + $pomak; $i < 5; $i++ )
			echo '<img src="empty.png" alt="slika" class="zvjezdica" height="20" width="20">';?>

		<a href=<?php echo '"' . dirname($_SERVER['REQUEST_URI'])  . "/amazon.php?id=" .  $proizvod->id . '"'?>
			 class="broj_recenzija"> <?php echo '(' . $proizvod->broj_recenzija . 'reviews)'; ?> </a>
		<p class="opis"> <?php echo $proizvod->opis  ?> </p>
		</div>
	<?php } ?>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>

	
