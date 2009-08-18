<?php

include_once('../config.php');
include_once('../include/functions.php');

/*
Viser lenker til artikler fra SNL

Info fra SNL: 

  Man s�ker i leksikonet med f�lgende url:
  http://www.snl.no/.search?query=test&format=xml&x=0&y=0
  query er sp�rreordet, x og y er antall �nskede svar og startpunkt, 
  format er hvilket format dere �nsker resultatet i. Vi st�tter 
  formatene html, xml og json.

  Strengen kan ogs� ta parameteren authorized som kan v�re 0 eller 1, 
  avhengig av om man vil s�ke etter autorisert innhold, uautorisert, 
  eller begge deler (i siste tilfellet utelater man parameteren).

NB! Det ser ikke ut som x- og y-parameterne i URLen til SNL funker! 

*/

if (!empty($_GET['q'])) {
	
  $data = json_decode(file_get_contents("http://www.snl.no/.search?query=" . $_GET['q'] . "&format=json&x=" . $config['modules']['snl']['limit'] . "&y=0"));
  
  if ($data) {
  	echo("<ul>");
    foreach ($data->result->list as $item) {
      echo('<li><a href="http://snl.no/' . $item->link . '">' . $item->title . '</a></li>');
    }
    echo("</ul>");
  }
	
}

?> 