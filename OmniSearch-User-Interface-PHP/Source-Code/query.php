<?php

// Turn off error reporting
error_reporting(0);

// If both mirna and term query parameters are missing
if(!isset($_GET['mirna']) && !isset($_GET['term'])) {
    // Navigate to the error page
    header('Location: /error.php');
    exit;
}

// Try
try {
    // Determine which type of query is requested
    $is_mirna = isset($_GET['mirna']);
    $is_term = isset($_GET['term']);

    // If mirna query
    if ($is_mirna) {
        // Store the mirna value
        $mirna = $_GET['mirna'];
    }
    // Else
    else {
        // Store the mesh term value
        $term = $_GET['term'];
    }

    // Build the query string
    $query = 'prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'SELECT ?label ' .
        'WHERE { ' .
        '?parent rdfs:label ' . ($is_mirna ? '"human_miRNA"' : '"phenotype"') . ' . ' .
        '?child rdfs:subClassOf ?parent . ' .
        '?child rdfs:label ?label ' .
        'FILTER REGEX(LCASE(?label), "' . ($is_mirna ? $mirna : $term) . '") ' .
        '} ORDER BY ?label';

    // Build the query url
    $url = 'http://localhost:3030/OmniStore/query?query=' . urlencode($query);

    // Set the request header options to accept sparql-results+json
    $options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));

    // Create the stream context
    $stream_context = stream_context_create($options);

    // If the query failed
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        // Inform the user that the store is unavailable
        echo '<h5>OmniStore Unavailable</h5>';
        exit;
    }

    // If decoding the json string failed
    if (($json = json_decode($json, true)) === null) {
        // Inform the user that the store is unavailable
        echo '<h5>OmniStore Unavailable</h5>';
        exit;
    }

    // Used for html tab index
    $i = 0;
    // Holds html markup
    $results = '';

    // Loop through the labels
    foreach ($json['results']['bindings'] as $obj) {
        // Concatenate a paragraph element with the current tab index and label
        $results .= '<p tabindex="' . ++$i . '">' . $obj['label']['value'] . '</p>';
    }

    // If no results were generated
    if ($results === '') {
        // Inform the user that no results were found
        $results = '<h5>No Results Found</h5>';
    }

    // Echo the results to the client
    echo $results;
}
// Catch
catch(Exception $ex) {
    // Log the error
    error_log($ex);

    // Inform the user that the store is unavailable
    echo '<h5>OmniStore Unavailable</h5>';
    exit;
}

?>