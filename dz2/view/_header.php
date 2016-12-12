<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Amazon</title>
	<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
</head>
<body>
	<fieldset>
	<h1 id="naslov"><?php echo $title; ?></h1>
	<?php 
	$arr = explode('?', $_SERVER['REQUEST_URI']);
	$dirname =dirname( $arr[0] ); 
	if(!isset($_SESSION['username'])){ ?>
		<form action=<?php echo '"' . $dirname  . "/amazon.php?rt=user" . '"';?> method="POST">
			<button type="submit" name="gumb" value="register" class="gumb">Register</button>
			<button type="submit" name="gumb" value="login" class="gumb">Login</button>
		</form>
	<?php
	}
	else{?>
		<form action=<?php echo '"' . $dirname  . "/amazon.php?rt=user/logout" . '"';?> method="POST">
			<button type="submit" name="gumb" value="logout" class="gumb">Logout</button>
		</form>
	<?php } ?>
	</fieldset>

