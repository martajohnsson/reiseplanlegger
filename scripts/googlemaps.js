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
