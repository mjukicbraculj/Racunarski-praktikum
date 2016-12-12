<?php

// .. Npr. $vlakovi[1]["cilj"] je "Osijek".
$vlakovi = array
(
	array( "start" => "Zagreb", "cilj" => "Split",  "polazak" => "8h",  "cijena" => 150 ),
	array( "start" => "Zagreb", "cilj" => "Osijek", "polazak" => "12h", "cijena" => 100 ),
	array( "start" => "Osijek", "cilj" => "Rijeka", "polazak" => "10h", "cijena" => 200 ),
	array( "start" => "Rijeka", "cilj" => "Zagreb", "polazak" => "21h", "cijena" => 120 ),
	array( "start" => "Zagreb", "cilj" => "Split",  "polazak" => "15h", "cijena" => 140 ),
	array( "start" => "Split",  "cilj" => "Zagreb", "polazak" => "11h", "cijena" => 150 ),
	array( "start" => "Zagreb", "cilj" => "Split",  "polazak" => "5h",  "cijena" => 120 ),
	array( "start" => "Osijek", "cilj" => "Rijeka", "polazak" => "20h", "cijena" => 220 ),
	array( "start" => "Split",  "cilj" => "Osijek", "polazak" => "14h", "cijena" => 250 ),
);

if(isset($_GET["start"]) && !isset($_GET["cilj"])){
  $kuda = array();
  for($i = 0; $i < count($vlakovi); $i++){
    if($vlakovi[$i]["start"] === $_GET["start"]){
      array_push($kuda, $vlakovi[$i]["cilj"]);
    }
  }
  echo json_encode(array_unique($kuda));
}
else if(isset($_GET["start"]) && isset($_GET["cilj"])){
  $kuda = array();
  for($i = 0; $i < count($vlakovi); $i++){
    if($vlakovi[$i]["start"] === $_GET["start"] && $vlakovi[$i]["cilj"] ===$_GET["cilj"]){
      array_push($kuda, array("polazak" => $vlakovi[$i]["polazak"], "cijena" => $vlakovi[$i]["cijena"]));
    }
  }
  echo json_encode(($kuda));

}
?>
