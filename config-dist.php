<?php

/*

Copyright 2009 ABM-utvikling

This file is part of "Podes reiseplanlegger".

"Podes reiseplanlegger" is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

"Podes reiseplanlegger" is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with "Podes reiseplanlegger".  If not, see <http://www.gnu.org/licenses/>.

Source code available from: 
http://github.com/pode/reiseplanlegger/

*/

// Setter navnet på applikasjonen
$config['app_title'] = 'Podes reiseplanlegger';

// Hvor mange treff skal maksimalt hentes ved søk? 
$config['main_limit'] = 100;
// Hvor mange treff skal vises pr "side" i hovedvisningen
$config['mainPerPage'] = 10;

/*
GOOGLE ANALYTICS

Du kan bruke Google analytics for å se statistikk for bruken av denne applikasjonen. 
Du trenger en kode for å aktivisere denne tjenesten, dette jkan du få her: 
https://www.google.com/analytics/
Eksempel: $config['google_analytics'] = "UA-12345678-1";
Dersom du ikke oppgir noen kode nedenfor vil Google Analytics ikke bli aktivert. 
*/

$config['google_analytics'] = "";

/*
BIBLIOTEK

Her konfigureres de bibliotekene det skal være mulig å søke i, og 
de opplysningene som trengs for å utføre søket. Rekkefølgen her
bestemmer rekkefølgen når bibliotekene skal velges ved søk. 

Opplysninger som trengs: 
title: navn på biblioteket 
z3950 ELLER sru og item_url
z3950: tilkoblings-streng for Z39.50
sru: tilkoblingsstreng for SRU
item_url: grunn-URL for postvisning i katalogen
*/
$config['libraries']['deich'] = array(
	'title' => 'Deichmanske', 
	'z3950' => 'z3950.deich.folkebibl.no:210/data'
);
$config['libraries']['pode'] = array(
	'title'    => 'Pode', 
	'sru'      => 'http://torfeus.deich.folkebibl.no:9999/biblios', 
	'item_url' => 'http://dev.bibpode.no/cgi-bin/koha/opac-detail.pl?biblionumber='
);
/*
$config['libraries']['bibsys'] = array(
	'title' => 'BIBSYS', 
	'z3950' => 'z3950.bibsys.no:2100/BIBSYS', 
);
$config['libraries']['trondheim'] = array(
	'title' => 'Trondheim folkebibliotek', 
	'z3950' => 'z3950.trondheim.folkebibl.no:210/data', 
);
$config['libraries']['bergen'] = array(
	'title' => 'Bergen offentlige', 
	'z3950' => 'z3950.bergen.folkebibl.no:210/data', 
);
*/

/*
DIVERSE MELDINGER
*/

$config['msg'] = array( 
	'zero_hits' => '<p>Ingen treff...</p>', 
);

/*
AUTOSUGGEST

Konfigurerer hvordan autosuggest i søkeboksen oppfører seg. 
show_dewey [true|false] - slår av og på visningen av Dewey-nummer
maxresults 10 - bestemmer antall forslag som skal vises
*/

$config['autosuggest'] = array(
	'show_dewey' => false, 
	'maxresults' => 10, 
);

/*
MODULER

Moduler konfigureres med et array på formen
$config['modules']['MODUL'] = array();
der MODUL tilsvarer den midterste delen av filnavnet modulen 
er implementert i: mod.MODUL.php. 

Rekkefølgen på modulene nedenfor bestemmer rekkfølgen modulene
vises i på siden. (Men dette kan overstyres av brukerne, som selv kan
flytte rundt på modulene.)

Alle moduler har minst to parametere: 
'enabled': true eller false, dvs om modulen er slått av eller på. 
'title': tittelen som vises i modul/widget-boksen

Dersom modulen inneholder en liste med elementer hvor antallet 
elementer skal kunne begrenses ved hjelp av en parameter gjøres 
dette med en parameter som heter 'limit'.
*/

$config['modules']['language'] = array(
  'enabled' => true, 
  'title' => "Lærebøker og språkkurs",  
  'limit' => 5, 
);

$config['modules']['travel'] = array(
  'enabled' => true,
  'title' => "Reiseskildringer",  
  'limit' => 5, 
);

$config['modules']['stories'] = array(
  'enabled' => true,
  'title' => "Fortellinger",  
  'limit' => 5, 
);

$config['modules']['food'] = array(
  'enabled' => true,
  'title' => "Mat og matlaging",  
  'limit' => 5, 
);

$config['modules']['culture'] = array(
  'enabled' => true,
  'title' => "Kulturhistorie",  
  'limit' => 5, 
);

$config['modules']['snl'] = array(
  'enabled' => true, 
  'title' => "Store norske leksikon", 
  'limit' => 1,
);

$config['modules']['weather'] = array(
  'enabled' => true, 
  'title' => "Været", 
);

/* 
For at kart skal fungere må man ha en "Google Maps API key", dette får man her: 
http://code.google.com/intl/en/apis/maps/
*/
$config['modules']['map'] = array(
  'enabled' => true, 
  'title' => "Kart", 
  'api_key' => 'Din API key limes inn her'
);

$config['modules']['debug'] = array(
  'enabled' => true, 
  'title' => "Debug", 
);

?>