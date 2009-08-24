<?php

include_once('../config.php');
include_once('../include/functions.php');

/*
Viser lenker til artikler fra SNL

Info fra SNL: 

  Man s�ker i leksikonet med f�lgende url:
  http://www.snl.no/.search?query=test&format=xml&size=0&y=0
  query er sp�rreordet, x og y er antall �nskede svar og startpunkt, 
  format er hvilket format dere �nsker resultatet i. Vi st�tter 
  formatene html, xml og json.

  Strengen kan ogs� ta parameteren authorized som kan v�re 0 eller 1, 
  avhengig av om man vil s�ke etter autorisert innhold, uautorisert, 
  eller begge deler (i siste tilfellet utelater man parameteren).

NB! Det ser ikke ut som x- og y-parameterne i URLen til SNL funker! 
Ny info: parameteren size skal avgrense antall treff

*/

if (!empty($_GET['q'])) {
	
  $data = json_decode(file_get_contents("http://www.snl.no/.search?query=" . $_GET['q'] . "&format=json&size=" . $config['modules']['snl']['limit'] . "&y=0"));
  
  if ($data) {
    foreach ($data->result->list as $item) {
      echo('<p><a href="http://snl.no/' . $item->link . '">' . $item->title . '</a>, ' . strip_tags($item->shortview) . '<br /><a href="http://snl.no/' . $item->link . '">Les mer i Store norske leksikon</a></p>');
    }
  }
	
}

?> 