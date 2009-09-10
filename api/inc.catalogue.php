<?php

/*
Funksjoner som trengs for � s�ke i katalogen med Z39.50 eller SRU. 
*/

function z_search($q, $limit = 20, $start = 1, $order = 'descending', $sortBy = 'year', $showAuthor = false) {
	
	//sti til XSL
	$xsl_url = '../xsl/bokliste.xsl';
	
	//oppretter DOM-dok med XML-data
	$xml = new DOMDocument;
	$xml->loadXML(get_ccl_results_as_xml($q, $limit));
	
	//teller antallet <record>-noder (antall s�ketreff)
	$nodeList = $xml->getElementsByTagName('record');
	$hits = $nodeList->length;
	
	//ingen treff
	if ($hits==0) 
	{
		return false;
	}
	//treff, XML blir transformert og skrevet ut
	else
	{
		// echo "<p>Antall treff: $hits</p>\n";
		
		$params = array(array('namespace' => '', 'name' => 'url_ext', 'value' => "type=z39.50"), // TODO: Brukes denne? 
					    array('namespace' => '', 'name' => 'sortBy',  'value' => $sortBy),
					    array('namespace' => '', 'name' => 'order',   'value' => $order), 
					    array('namespace' => '', 'name' => 'target',  'value' => "remote"), 
					    array('namespace' => '', 'name' => 'visForfatter',  'value' => $showAuthor)); 
	
		return transformToHTML($xml, $xsl_url, $params);
	}
	
}

function sru_search($q, $limit = 20, $start = 1, $order = 'descending', $sortBy = 'year', $showAuthor = false) {
		
	//oppretter URL til KOHA med cql
	$xml_url = getSRUURL($q, $start, $limit);
	//sti til XSL
	$xsl_url = '../xsl/boklistesru.xsl';
	
	//henter XML-data
	$xml_data = file_get_contents($xml_url) or exit("Feil");
	//fjerner namespace
	$xml_data = str_replace("<record xmlns=\"http://www.loc.gov/MARC21/slim\">", "<record>", $xml_data);
	
	//oppretter DOM-dok med XML-data
	$xml = new DOMDocument;
	$xml->loadXML($xml_data);
	
	//teller antallet <recordData>-noder (antall s�ketreff)
	$nodeList = $xml->getElementsByTagName('recordData');
	$hits = $nodeList->length;
	
	if ($hits > 0) {
	
	//parametere til XSL
	$params = array(array('namespace' => '', 'name' => 'url_ext', 'value' => "type=sru"), // TODO: Brukes denne? 
					array('namespace' => '', 'name' => 'sortBy',  'value' => $sortBy),
					array('namespace' => '', 'name' => 'order',   'value' => $order), 
					array('namespace' => '', 'name' => 'target',  'value' => "remote"),
					array('namespace' => '', 'name' => 'visForfatter',  'value' => $showAuthor),  
					array('namespace' => '', 'name' => 'showHits',  'value' => "false"));
	
	//transformerer til HTML
	return transformToHTML($xml, $xsl_url, $params);

	} else {
	
		return false;
		
	}

}

function get_ccl_results_as_xml($ccl, $limit) {

	/*
	henter funksjonene i catalog.php, catalog.php inneholder
	funksjoner for � hente ut katalogdata fra z39.50-servere
	*/
	require_once '../include/catalog.php';
	
	$out = '';
	
	/*
	hvis ikke ccl-parameteren er oppgitt f�r man en tom XML-struktur
	tilbake med records som rotnode
	*/
	if (!isset($ccl))
	{
		
		$out .= "<records>\n</records>";
	} 
	/*
	hvis ccl-parameteren er satt f�r man MARCXML basert p� ccl-
	parameteren tilbake
	*/
	else
	{
		
		$out .= "<records>\n";
		/*
		kj�rer funksjonen yazCclArray som returnerer en array med
		MARCXML-data basert p� $query. syntaksen er 'normarc'. mot
		deichmanske kan denne byttes til hvertfall USMARC og MARC21
		*/
		$fetch = yazCclArray($ccl, 'normarc', $limit);
		/*
		henter ut verdien med n�kkelen 'result'. det er her selve
		dataene ligger lagret. $fetch-arrayen har ogs� en verdi med
		n�kkel 'hits' som forteller hvor mange records $fetch inneholder
		*/
		$data = $fetch['result'];
		//g�r gjennom $data-arrayen
		foreach ($data as $record)
		{
			//splitter p� nylinjetegn
			$lines = explode("\n", $record);
			/*
			overskriver den f�rste noden i hver record med en
			'<record>'-node. dette gj�r at namespacet blir fjernet
			og gj�r parsing og transformering av XML lettere
			*/
			$lines[0] = "<record>";
			/*
			samler arrayen $lines til en streng og konverterer til
			utf-8
			*/
			$out .= utf8_encode(implode("\n", $lines));
		}
		$out .= "</records>";
	}
	
	return $out;
	
}

?>
