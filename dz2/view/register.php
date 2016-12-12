
<?php require_once __SITE_PATH . '/view/_header1.php'; ?>

<?php $arr = explode('?', $_SERVER['REQUEST_URI']);
		$dirname =dirname( $arr[0] ); ?>
<form method="post" action=<?php echo '"' . $dirname  . "/amazon.php?rt=user/register" . '"'?>>
		</br>
		Odaberite korisničko ime:
		<input type="text" name="username" />
		<br />
		Odaberite lozinku:
		<input type="password" name="password" />
		<br />
		Vaša mail-adresa:
		<input type="text" name="email" />
		<br />
		<button type="submit">Stvori korisnički račun!</button>
	</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
