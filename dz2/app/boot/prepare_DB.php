<?php

// Manualno inicijaliziramo bazu ako već nije.
require_once 'db.class.php';

$db = DB::getConnection();

try
{
	$st = $db->prepare( 
		'CREATE TABLE IF NOT EXISTS proizvodi (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'naziv varchar(255) NOT NULL,' .
		'opis varchar(1000) NOT NULL,' .
		'cijena int NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

echo "Napravio tablicu proizvodi.<br />";

try
{
	$st = $db->prepare( 
		'CREATE TABLE IF NOT EXISTS recenzije (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_proizvoda int NOT NULL,' . 
		'autor varchar(20) NOT NULL,' .
		'ocjena enum("1", "2", "3", "4", "5") NOT NULL,' .
		'tekst varchar(1000) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }

echo "Napravio tablicu recenzije.<br />";


try
{
	$st = $db->prepare( 
		'CREATE TABLE IF NOT EXISTS UserList (' .
		'username varchar(20) NOT NULL PRIMARY KEY,' .
		'password varchar(255) NOT NULL,' .
		'email varchar(30),' . 
		'reg_seq varchar(30),' .
		'has_registered varchar(30))'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

echo "Napravio tablicu users.<br />";


// Ubaci neke proizvode unutra
try
{
	$st = $db->prepare( 'INSERT INTO proizvodi( naziv, opis, cijena) VALUES ( :naziv, :opis, :cijena)' );

	$st->execute( array( 'naziv' => 'Bosch MCM3100W multipraktik ', 'opis' => htmlentities(' Dimenzije: širina: 22,00 cm, 
		dubina: 26,00 cm, visina: 37,50 cm. Snaga: 800W, 2 brzine + pulsna funkcija rada, posuda 2.3l (0,8kg tijesta)
		multifunkcijski nož od nehrđajućeg čelika, ravnomjerna obrada namjrnica, disk za rezanje ploški i ribanje, 
		disk za tučeno vrhnje i bjelanjke, nastavci za izradu tijesta, sigurnosno zaključavanje, gumirane vakuum nožice, BPA free.
		Nastavci perivi u perilici posuđa.'),
		'cijena' => '1200,99' ) );
	$st->execute( array( 'naziv' => 'Whirlpool FSCR70211 A+++ perilica rublja ', 'opis' => htmlentities('Dimenzije: širina: 59,50 cm, dubina: 61,00 cm, 
		visina: 85,00 cm.Model: FSCR70211. Tehnologija 6. čulo – 6th sense. Kapacitet: 7 kg. Brzina okretaja: 1200 okretaja/min. Inverter motor.
		Cycle Speed – prilagođeno okretanje bubnja na osnovi količine rublja. Colours 15 – čuva boje prilikom pranja.
		Stain 15 – uklanja najtvrdokornije mrlje kao što su krv, trava, blato ili kava na samo 15° C. Odgoda pranja.
		Kontrola ravnoteže. Razina buke pranje/centrifugiranje: 53dB / 75dB. Energetska učinkovitost: A+++. 10 godina garancije na motor.'),
		'cijena' => '2600, 99' ) );
	$st->execute( array( 'naziv' => 'Whirlpool AZA799 sušilica rublja', 'opis' => htmlentities('    Dimenzije: širina: 60,00 cm, dubina: 63,00 cm, 
		visina: 85,00 cm. Model: AZA799. Kondenzacijska sušilica, toplinska pumpa. Kapacitet: 7kg. LCD display. Razina buke: 69dB.
		Energetska učinkovitost: A.'),
		'cijena' => '4023,99' ) );
	$st->execute( array( 'naziv' => 'Tenor stolić bijeli', 'opis' => htmlentities(' Dimenzije: širina: 73,00 cm, dubina: 73,00 cm, visina: 46,00 cm, 
		s policom dim. 73×73×46 cm debljina gornje ploče 25 mm, kaljeno staklo debljine 4 mm, bijeli.'),
		'cijena' => '650,99' ) );
	$st->execute( array( 'naziv' => 'Flora lux trosjed s ležajem i spremnikom', 'opis' => htmlentities('Dimenzije: Širina: 236.00 cm, dubina: 83.00 cm,
	 	visina: 100,00 cm. Trosjed s ležajem i spremnikom. Bež tkanina. Dim. ležaja: 190x160 cm.'), 
		'cijena' => '3244,99' ) );
}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }

echo "Ubacio proizvode u tablicu proizvodi.<br />";

// Ubaci neke recenzije unutra
try
{
	$st = $db->prepare( 'INSERT INTO recenzije( id_proizvoda, autor, ocjena, tekst) VALUES ( :id_proizvoda, :autor, :ocjena, :tekst)' );

	$st->execute( array( 'id_proizvoda' => '2', 'autor' => 'mirica', 'ocjena' => '1',  'tekst' => 'Stalno se kvari' ) );
	$st->execute( array( 'id_proizvoda' => '3', 'autor' => 'mirica', 'ocjena' => '2',  'tekst' => 'Ima problem s kabelom za struju.' ) );
	$st->execute( array( 'id_proizvoda' => '5', 'autor' => 'mirica', 'ocjena' => '5',  'tekst' => 'Jako udoban za spavanje' ) );
}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }

echo "Ubacio recenzije u tablicu recenzije.<br />";
