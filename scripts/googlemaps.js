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

/**
 *Funksjon som tar imot parametre fra s�kesiden og skriver ut riktig kart
 *Funksjonen tar imot 5 parametre (lengdegrad, breddegrad, kartbredde, karth�yde og zoom-niv�)
 *map variabelen m� n�es fra to funksjoner og er derfor deklarert "globalt"
 */
var map;
function map(lat, lng, width, height, zoom)
{ 
	var container = document.getElementById("map");
	map = new GMap2(container, {size:new GSize(width,height)});
    map.setCenter(new google.maps.LatLng(lat, lng), zoom);

	var UI = map.getDefaultUI();
	UI.zoom.scrollwheel = false; //zoom p� scroll-hjulet til musa true/false
	map.setUI(UI);
}

/**
 *Funksjon som legger p� de forskjellige lagene
 *switchLayer blir kallt fra hoveddokumentet og legger p� riktig lag p� kartutsnittet
 *Funksjonen tar to parametre (checked(om den er huket av) og layer(hvilket lag det gjelder)).
 */
var pano = new GLayer("com.panoramio.all");
var wiki = new GLayer("org.wikipedia.no");//ved � bytte ut .no med feks .en vil man f� bare engelske wikipediaoppslag
var tube = new GLayer("com.youtube.all");

function switchLayer(checked,layer)
{
  if(checked)map.addOverlay(layer);
  if(!checked)map.removeOverlay(layer);
}
