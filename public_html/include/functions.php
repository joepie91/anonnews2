<?php
if(!isset($_ANONNEWS))
{
	die("You are not supposed to be here.");
}





$langfiles['en']="en.lang";  // English
$langfiles['de']="de.lang";  // German
$langfiles['fr']="fr.lang";  // French
$langfiles['nl']="nl.lang";  // Dutch
$langfiles['it']="it.lang";  // Italian
$langfiles['es']="es.lang";  // Spanish
$langfiles['no']="no.lang";  // Norwegian
$langfiles['se']="se.lang";  // Swedish
$langfiles['fi']="fi.lang";  // Finnish
$langfiles['kr']="kr.lang";  // Korean
$langfiles['ru']="ru.lang";  // Russian
$langfiles['mk']="mk.lang";  // Macedonian
$langfiles['ba']="bs.lang";  // Bosnian
$langfiles['pl']="pl.lang";  // Polish
$langfiles['si']="si.lang";  // Slovenian


/*function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}*/
?>
