<?php

class Recenzija{
	
	protected $id, $id_proizvoda, $autor, $ocjena, $tekst;
	
	function __construct( $id, $id_proizvoda, $autor, $ocjena, $tekst ){
		
		$this->id = $id;
		$this->id_proizvoda = $id_proizvoda;
		$this->autor = $autor;
		$this->ocjena = $ocjena;
		$this->text = $tekst;
	}
	
	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
	
	
}