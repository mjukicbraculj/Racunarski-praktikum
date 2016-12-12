<?php 

class AmazonController extends BaseController
{
	public function amazon() 
	{
		// Samo preusmjeri na users podstranicu.
		header( 'Location: ' . __SITE_URL . '/amazon.php?rt=proizvodi' );
	}
}

?>
