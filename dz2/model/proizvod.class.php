<?php

class Proizvod{
	
	protected $id, $naziv, $opis, $cijena, $prosjecna_ocjena, $broj_recenzija;
	
	function __construct( $id, $naziv, $opis, $cijena, $prosjecna_ocjena = 0, $broj_recenzija = 0 ){
		
		$this->id = $id;
		$this->naziv = $naziv;
		$this->opis = $opis;
		$this->cijena = $cijena;
	}
	
	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
	
	
}
