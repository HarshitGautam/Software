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

    // Get the query parameters
    $mirna = $_GET['mirna'];
    $term = $_GET['term'];
    $sort_by = $_GET['sort_by'];
    $predicted_by = $_GET['predicted_by'];
    $publication_filter = $_GET['publication_filter'];
    $format = $_GET['format'];
    $selected = $_GET['selected'];

    // Build the query string
    $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
        'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
        'SELECT * ' .
        'WHERE { ' .
        '{ ' .
        'SELECT ?gene_symbol ' .
        '(GROUP_CONCAT(DISTINCT ?g_id; SEPARATOR=",") AS ?gene_id) ' .
        '(MAX(IF(BOUND(?mdb_score), ?mdb_score, 0)) AS ?mirdb_score) ' .
        '(MAX(IF(BOUND(?ts_score), ?ts_score, 0)) AS ?targetscan_score) ' .
        '(MAX(IF(BOUND(?mrnd_score), ABS(?mrnd_score), 0)) AS ?miranda_score) ' .
        '(GROUP_CONCAT(DISTINCT ?pmid; SEPARATOR=",") AS ?pubmed_ids) ' .
        'WHERE { ' .
        '?mirna rdfs:label "' . $mirna . '" . ' .
        '?prediction obo:RO_0000057 ?mirna . ' .
        '?prediction obo:RO_0000057 ?target . ' .
        '?target rdf:type obo:NCRO_0000025 . ' .
        '?target rdfs:label ?gene_symbol . ' .
        '?target obo:OMIT_0000109 ?g_id . ' .
        'OPTIONAL { ' .
        '?prediction rdf:type obo:OMIT_0000020 . ' .
        '?prediction obo:OMIT_0000108 ?mdb_score ' .
        '} . ' .
        'OPTIONAL { ' .
        '?prediction rdf:type obo:OMIT_0000019 . ' .
        '?prediction obo:OMIT_0000108 ?ts_score ' .
        '}. ' .
        'OPTIONAL { ' .
        '?prediction rdf:type obo:OMIT_0000021 . ' .
        '?prediction obo:OMIT_0000108 ?mrnd_score ' .
        '}. ' .
        'OPTIONAL { ' .
        '?mesh_term rdfs:label "' . $term . '" . ' .
        '?pmed_info obo:RO_0000057 ?target . ' .
        '?pmed_info obo:BFO_0000051 ?mesh_term . ' .
        '?pmed_info obo:OMIT_0000151 ?pmid ' .
        '} ' .
        '} ' .
        'GROUP BY ?gene_symbol ' .
        'ORDER BY DESC(?' . $sort_by . '_score) ' .
        '} ' .
        ($predicted_by === 'all' ? 'FILTER (?mirdb_score != 0 && ?targetscan_score != 0 && ?miranda_score != 0) ' : '') .
        ($publication_filter === 'all' ? '' :
            ($publication_filter === 'has_pubmed' ? 'FILTER (BOUND(?pubmed_ids)) ' : 'FILTER (!BOUND(?pubmed_ids)) ')
        ) .
        '} ';

    // Build the query url
    $url = 'http://localhost:3030/OmniStore/query?query=' . urlencode($query);

    // If the query failed
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        // Navigate to the error page
        header('Location: /error.php');
        exit;
    }

    // If decoding the json string failed
    if (($json = json_decode($json, true)) === null) {
        // Navigate to the error page
        header('Location: /error.php');
        exit;
    }

    // Holds file contents
    $text = '';
    // Holds filename
    $filename = '';

    // If download all targets
    if ($format === 'tsv' || $format === 'csv') {
        // Loop through all targets
        foreach ($json['results']['bindings'] as $target) {
<<<<<<< HEAD
            // Holds pumbed id count
            $pubmed_count = 0;
            // If pubmed ids are available
            if (!empty($target['pubmed_ids']['value'])) {
                // Get the pubmed count
                $pubmed_count = count(explode(',', $target['pubmed_ids']['value']));
            }

            // If tab-separated values
            if ($format === 'tsv') {
                // Concatenate the target information
                $text .= $target['gene_symbol']['value'] . "\tmiRDB\t\t# of filtered publications: " . $pubmed_count . "\t\t\t" . $target['gene_symbol']['value'] . "\r\n\tTargetScan\t" . $mirna . "-Specific\tmRNA\r\n\tmicroRNA.org\r\n";
            } // Else if comma-separated values
            else if ($format === 'csv') {
                // Concatenate the target information
                $text .= $target['gene_symbol']['value'] . ",\"miRDB\r\nTargetScan\r\nmicroRNA.org\",\"# of filtered publications: " . $pubmed_count . "\r\n" . $mirna . "-Specific\",\"" . $target['gene_symbol']['value'] . "\r\nmRNA\"\r\n";
=======
            // If tab-separated values
            if ($format === 'tsv') {
                // Concatenate the target information
                $text .= $target['gene_symbol']['value'] . "\tmiRDB\t\tAll\t\t\t" . $target['gene_symbol']['value'] . "\r\n\tTargetScan\t" . $mirna . "-Specific\tmRNA\r\n\tmicroRNA.org\r\n";
            } // Else if comma-separated values
            else if ($format === 'csv') {
                // Concatenate the target information
                $text .= $target['gene_symbol']['value'] . ",\"miRDB\r\nTargetScan\r\nmicroRNA.org\",\"All\r\n" . $mirna . "-Specific\",\"" . $target['gene_symbol']['value'] . "\r\nmRNA\"\r\n";
>>>>>>> origin/master
            }
        }

        // Save tsv file with .txt extension
        if ($format === 'tsv') {
            $format = 'txt';
        }

        // Create the filename
        $filename = 'Query_Results_ for_' . $mirna . '-' . date('Y-m-d') . '.' . $format;
    } // Else if download selected targets
    else if ($format === 'txt') {
        // Split the selected string into a string array
        $selected = preg_split('/,/', $selected, -1, PREG_SPLIT_NO_EMPTY);

        // Get the selected targets count
        $select_count = count($selected);

        // Loop through each target label
        foreach ($json['results']['bindings'] as $target) {
            // Select all or current symbol is selected
            if ($select_count === 0 || in_array($target['gene_symbol']['value'], $selected, true)) {
                // Concatenate the target label
                $text .= $target['gene_symbol']['value'] . "\r\n";
            }
        }

        // Create the filename
        $filename = 'Target_List_ for_' . $mirna . '-' . date('Y-m-d') . '.txt';
    }

    // Set the Response Header properties and echo the file contents
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($text));
    echo $text;

} // Catch
catch (Exception $ex) {
    // Log the error
    error_log($ex);

    // Navigate to the error page
    header('Location: /error.php');
    exit;
}
