<?php

// Ovde postavi prave parametre za logovanje na bazu
$dbhost = 'ime hosta';
$dbuser = 'username';
$dbpass = 'password';
$dbname = 'ime baze';

//kreiranje linka na bazu
  $dblink = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

//Provera konekcije
  if ($dblink->connect_errno) {
     printf("Nema konekcije na bazu");
     exit();
  }

//postavljanje enkodinga u aplikaciju
  $result = $dblink->query("set names 'utf8'");
//deset redova
  $result = $dblink->query("SELECT * FROM imeTabele LIMIT 10");

//Matrica za rezultate
  $dbdata = array();

//Rezultati u matricu
  while ( $row = $result->fetch_assoc())  {
	$dbdata[]=$row;
  }

//Izlaz na ekran ili u fajl
//echo json_encode($dbdata);
    $fp = fopen('PolenData.json', 'w');
    fwrite($fp, json_encode($dbdata));
    fclose($fp);
?>
