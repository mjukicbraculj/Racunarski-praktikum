<?php require_once __SITE_PATH . '/view/_header1.php'; ?>


<?php $arr = explode('?', $_SERVER['REQUEST_URI']);
		$dirname = dirname($arr[0]); ?>

<form method="post" action=<?php echo '"' . $dirname  . "/amazon.php?rt=user/login" . '"'?>>
		</br>
		Korisničko ime:
		<input type="text" name="username" />
		</br>
		Lozinka:
		<input type="password" name="password" />
		<br />
		<button type="submit">Ulogiraj se!</button>
	</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>


