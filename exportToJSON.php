<?php
ini_set('display_errors', 'On');
// Ovde postavi prave parametre za logovanje na bazu
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'TestDB';

#Ovde dodaj karaktere koji treba da se zamene
function replaceChars($string) {
  echo $string;
  $patterns = array();
  $patterns[0] = '/Å¾/';
 $patterns[1] = '/Ä‡/';
 $patterns[2] = '/Ä/';
  $patterns[3] = '/Å¡/';

  $replacements = array();
  $replacements[0] = 'ž';
  $replacements[1] = 'ć';
  $replacements[2] = 'č';
    $replacements[3] = 'š';

  $string= preg_replace($patterns, $replacements, $string);
  echo $string;
return $string;
}
//json_encode radi samo sa utf8 pa je nećemo koristiti
function json_encode2($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }
      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode2($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode2($k).':'.json_encode2($v);
      return '{' . join(',', $result) . '}';
    }
  }


//kreiranje linka na bazu
  $dblink = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
//Provera konekcije
  if ($dblink->connect_errno) {
     printf("Nema konekcije na bazu");
     exit();
  }
//postavljanje enkodinga u aplikaciju
  $result = $dblink->query("set names 'utf8'");
  $result = $dblink->query("SELECT * FROM latinTest");

//Matrica za rezultate
  $dbdata = array();
//Rezultati u matricu
  while ( $row = $result->fetch_assoc()){
    //Ovde dodaš problematične redove sa našim slovima
  $row["Prezime"]= replaceChars($row["Prezime"]);
	$dbdata[]=$row;
  }


//Izlaz na ekran ili u fajl
$strToWrite=json_encode2($dbdata);
echo "<br>".$strToWrite;

    $fp = fopen('/Users/ivicaca/Radni/PolenData.json','w');
    fwrite($fp, $strToWrite);
    fclose($fp);

?>
