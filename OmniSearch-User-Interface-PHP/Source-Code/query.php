<?php

// Try
try {
	// Turn off error reporting
	error_reporting(0);

	// Set the date timezone
    date_default_timezone_set('America/New_York');
	
	// Set the request header options to accept sparql-results+json
	$options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));

	// Create the stream context
	$stream_context = stream_context_create($options);
	
	// The SPARQL host url
    $host = 'http://localhost:3030/OmniStore/query?query=';

    // Get query parameters
    $type = $_GET['type'];
    $mirna = $_GET['mirna'];
    $mesh = $_GET['mesh'];

    // If type is mirna
    if($type == 'mirna') {
        // Build the query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'SELECT ?label ' .
            'WHERE { ' .
            '?child rdfs:subClassOf obo:NCRO_0000810 . ' .
            '?child rdfs:label ?label ' .
            'FILTER REGEX(?label, "' . $mirna . '", "i") ' .
            '} ' .
            'ORDER BY ?label';
    }
    // Else
    else {
        // Build the query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'SELECT ?plabel (GROUP_CONCAT(DISTINCT ?clabel; SEPARATOR=";") AS ?children) ' .
            'WHERE { ' .
			'?parent rdfs:label ?plabel . ' .
            'FILTER REGEX(?plabel, "' . $mesh . '", "i") ' .
			'?parent (rdfs:subClassOf)+ obo:OMIT_0000110 . ' .
			'OPTIONAL { ' .
			'?child rdfs:subClassOf ?parent . ' .
			'?child rdfs:label ?clabel . ' .
			'FILTER REGEX(?clabel, "' . $mesh . '", "i") ' .
			'} ' .
            '} ' .
            'GROUP BY ?plabel ' .
            'ORDER BY ?plabel ' .
            'LIMIT 20 ';
    }

    // Build the query url
    $url = $host . urlencode($query);

    // Check for apache jena server failure
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        // Inform the user that the store is unavailable
        echo json_encode(array('success' => false, 'error' => 'OmniStore Unavailable'));
        exit;
    }

    // Check for json decode failure
    if (($json = json_decode($json, true)) === null) {
        echo json_encode(array('success' => false, 'error' => 'OmniStore Unavailable'));
        exit;
    }

    // Check for empty result set
    if(count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
        echo json_encode(array('success' => false, 'error' => 'No Results Found'));
        exit;
    }

    // Holds tab index
    $i = 0;
    // Holds html markup
    $html = '';

    // Loop through the results
    foreach ($json['results']['bindings'] as $item) {
        // If type is mirna
        if($type == 'mirna') {
            // Concatenate a paragraph element with the current tab index and label
            $html .= '<p tabindex="' . $i++ . '">' . $item['label']['value'] . '</p>';
        }
        // Else
        else {
            // If there are no children mesh terms
            if(isset($item['children'])) {
                // Split the children string into an array using a semicolon as the delimiter
                $children = explode(';', $item['children']['value']);
                // Add a paragraph, including a tab index
                $html .= '<p tabindex="' . $i++ . '">' . $item['plabel']['value'] . '</p>';
                // Loop through the children
                foreach ($children as $child)
                    // Add a paragraph, including a tab index and the child class
                    $html .= '<p tabindex="' . $i++ . '" class="child">' . $child . '</p>';
            }
            // Else
            else {
                $html .= '<p tabindex="' . $i++ . '">' . $item['plabel']['value'] . '</p>';
            }
        }
    }

    // Echo the results to the client
    echo json_encode(array('success' => true, 'html' => $html));
    exit;
}
// Catch
catch(Exception $ex) {
    // Log the error
    error_log($ex);

    // Inform the user that the store is unavailable
    echo json_encode(array('success' => false, 'error' => 'OmniStore Unavailable'));
    exit;
}
?>