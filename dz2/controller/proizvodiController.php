<?php


class ProizvodiController extends BaseController{
	
	public function amazon(){
		
		$ps = new proizvodiService();
		
		$this->registry->template->title = 'Welcome to our web-shop!';
		$this->registry->template->proizvodiLista = $ps->getAllProizvodi();

		
		$this->registry->template->show( 'proizvodi_index' );
		
	}
	
	
}
