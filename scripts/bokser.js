// Sjekk om det er gitt noen verdi for "place" i URLen (query string)
if (getQueryVariable('place')) {

// Dette er en jQuery funksjon som automatisk kjøres når hele siden er lastet inn 
// (dvs, egentlig når DOMen er klar)
$(function(){

  // Gjør boksene til "widgets"
  // useCookies : true gjør at det brukes cookies for å huske tilstanden til boksene 
  // mellom sesjoner
  $.fn.EasyWidgets({
  	
  	behaviour : {
      useCookies : true
    },
  	
    i18n : {
      editText       : '<img src="images/widgets/edit.png"     alt="Rediger" width="16" height="16" />',
      closeText      : '<img src="images/widgets/close.png"    alt="Close"   width="16" height="16" />',
      collapseText   : '<img src="images/widgets/collapse.png" alt="Lukk"    width="16" height="16" />',
      cancelEditText : '<img src="images/widgets/edit.png"     alt="Avbryt"  width="16" height="16" />',
      extendText     : '<img src="images/widgets/extend.png"   alt="Close"   width="16" height="16" />'
    }
  });
  
  // Vis boksen som inneholder navn på sted, evt mulighet for å velge sted  
  $("#place").css({'visibility' : 'visible'});
  
  // Hent inn verdier fra URLen (query string) Denne funksjonen er definert lenger ned i fila
  var place = getQueryVariable('place');
  // var placeEng = place; 
  var geoId = getQueryVariable('geoId');
  
  // Bruk Google Translate for å oversette navnet som vi antar er på norsk, til engelsk
  // (Det funker å søke med norske navn mot hoved-fila fra GeoNames, men ikke mot fila
  // som gir oss koblingen mellom land og hovedstad.)
  // google.language.translate(place, "no", "en", function(result) {
  //   if (!result.error) {
  // 	  placeEng = result.translation;
  // }
  // });
  
  // Hent nødvendige geodata fra serveren
  // Ved suksess vil getgeo.php returnere et array som inneholder et element "result" som vil 
  // være antallet treff. Vi bruker dette for å bestemme videre gang i skriptet
  $.getJSON("api/getgeo.php", { geoId: geoId, place: place }, function(json){
    
   
    // Fjern inneholdet i boksen som viser sted/tid
    $("#place").find(".widget-content").text("");
    
    if (json.result == 0) {
        
        // INGEN TREFF, skriver ut en feilmelding
        
        $("#place").find(".widget-content").append("<p>Beklager, 0 treff. Pr&oslash;v &aring; s&oslash;ke etter et annet stedsnavn.</p>");
    
        // TODO: Kunne man fått til å foreslå alternative skrivemåter? 
    
    } else if (json.result == 1) {
    	
    	// ETT TREFF - lager bokser med info om stedet/landet
    	
    	// Her har vi sted og land på engelsk
    	var place_country = json.placeInfo.place + ", " + json.placeInfo.country;
    	// Oversett sted og land til norsk, og skriv ut info på siden
    	google.language.translate(place_country, "en", "no", function(result) {
		  if (!result.error) {
		    $("#place").find(".widget-content").append("<h3>" + result.translation + "</h3><p>Lokal tid: " + json.localTime + ".</p>");
		    
		    var langs = '';
		    // $("#place").find(".widget-content").append("<p>Velg språk:</p><ul id=\"lang-list\"></ul>");
		    jQuery.each($(json.placeInfo.lang), function() {
			  // $("#place").find("#lang-list").append("<li>" + this + "</li>");
			  langs = langs + this + ",";
		    });
		    // $("#place").find(".widget-content").append("</ul>");
		    
		    // Hent ut navn på sted og land, på norsk
			var split = result.translation.split(", "); 
			var place_nor = split[0];
			var country_nor = split[1];
	        
	    	// Gjør widgetene synlige
	    	$(".widget").css({'visibility' : 'visible'});
	    	  
	    	// Gå igjennom alle widgetene og legg til innhold
			jQuery.each($(".widget"), function() {
			  var this_widget = this;
			  target = this_widget.id.replace(/widget_/g, "");
					
			  // Vi sender den samme informasjonen til modulene, uavhengig av hva de skal gjøre for noe. 
			  // På denne måten slipper vi å vite noe om hver enkelt modul før vi kaller dem opp. 
			  $.get("api/index.php", { mod: target, 
			                           lat: json.placeInfo.lat, 
			                           lon: json.placeInfo.lon, 
			                           place: place_nor, 
			                           country: country_nor, 
			                           langs: langs, 
			                           q: getQueryVariable('place'),
			                           bib: getQueryVariable('bib')
			                          },
			    function(data){
			      $("#" + this_widget.id).find(".widget-content").text("");
			      $("#" + this_widget.id).find(".widget-content").append(data);
			      // Sørg for å aktivere eventuelle trekkspill
			  	  $(".trekkspill").accordion({ autoHeight: false, active: false });
			    });
			
			  });
		    
		  }
		});
    	
    } else {
    	
    	// FLERE TREFF - vis liste med mulighet for å velge hvilket sted som var ment
    	
    	$("#place").find(".widget-content").append('<h3>Velg sted</h3>');
    	var sort_order_type = "&sortBy=" + getQueryVariable('sortBy') + "&order=" + getQueryVariable('order') + "&type=" + getQueryVariable('type');
    	jQuery.each(json.links, function() {
    		
    		var this_place = this;
    		
	    	google.language.translate(this_place.place, "en", "no", function(result) {
			  if (!result.error) {
			    $("#place").find(".widget-content").append('<p><a href="?' + this_place.url + sort_order_type + '">' + result.translation + '</a></p>');
			  }
			});	
    		
         });

    }
  });

});

}

// From: http://www.webdeveloper.com/forum/showthread.php?t=166692
function getQueryVariable(variable)
{
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0; i<vars.length; i++)
	{
		pair = vars[i].split("=");
		if (pair[0] == variable)
		{
			str_arr = pair[1].split("+");
			return str_arr.join(" ");
		}
	}
	return "";
}