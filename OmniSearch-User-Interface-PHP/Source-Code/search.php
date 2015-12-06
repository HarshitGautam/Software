<?php

// Turn off error reporting
error_reporting(0);

// Set the date timezone
date_default_timezone_set('America/New_York');

// Set the header content type to json
header('Content-Type: application/json');

// If the type, mirna or term query parameters were not supplied
if(empty($_GET['type']) || empty($_GET['mirna'])) {
    // Navigate to the error page
    header('Location: /error.php');
    exit;
}

// Try
try {
    // Store the required query string parameters
    $type = $_GET['type'];
    $mirna = $_GET['mirna'];
    $sortby = $_GET['sortby'];

    if(!empty($_GET['term']))
        $term = $_GET['term'];
    else
        $term = '';

    if($sortby == 'mirdb')
        $sortby = '?s_m';
    else if($sortby == 'targetscan')
        $sortby = '?s_t';
    else
        $sortby = '?s_m';

    // Set the request header options to accept sparql-results+json
    $options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));

    // Create the stream context
    $stream_context = stream_context_create($options);

    // If search is requested
    if ($type === 'search') {
        // If the limit and page parameters were not supplied
        if (empty($_GET['limit']) || empty($_GET['page'])) {
            // Navigate to the error page
            header('Location: /error.php');
            exit;
        }

        // Store the limit and page query parameters
        $limit = $_GET['limit'];
        $page = $_GET['page'];

        // Build the COUNT query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX owl: <http://www.w3.org/2002/07/owl#> ' .
            'SELECT ?l ?i (GROUP_CONCAT(IF(BOUND(?m_s), ?m_s, ""); SEPARATOR="") AS ?s_m) (GROUP_CONCAT(IF(BOUND(?t_s), ?t_s, ""); SEPARATOR="") AS ?s_t) ' .
            'WHERE { ' .
            '?mirna rdfs:label "hsa-miR-125b-5p" . ' .
            '?p obo:RO_0000057 ?mirna . ' .
            '?p obo:RO_0000057 ?t . ' .
            'FILTER (?t != ?mirna) . ' .
            '?t rdfs:label ?l . ' .
            '?t obo:OMIT_0000109 ?i . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000020 . ' .
            '?p obo:OMIT_0000108 ?m_s ' .
            '} . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000019 . ' .
            '?p obo:OMIT_0000108 ?t_s ' .
            '} ' .
            '} ' .
            'GROUP BY ?l ?i';

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

        // Store the target count
        $count = count($json['results']['bindings']);

        // If the count is zero
        if ($count <= 0) {
            // Return false to the ajax request
            echo json_encode(array('result' => false));
            exit;
        }

        // If requesting all pages
        if ($limit === 'All') {
            // Set default page and offset values
            $page_count = 1;
            $page = 1;
            $offset = 0;
        } else {
            // Calculate the page count
            $page_count = ceil($count / $limit);
            // Clamp the page to (1, page_count)
            $page = max(1, min($page, $page_count));
            // Calculate the offset
            $offset = ($page - 1) * $limit;
        }

        // Build the query string
        $query =  'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX owl: <http://www.w3.org/2002/07/owl#> ' .
            'SELECT ?l ?i (GROUP_CONCAT(IF(BOUND(?m_s), ?m_s, ""); SEPARATOR="") AS ?s_m) (GROUP_CONCAT(IF(BOUND(?t_s), ?t_s, ""); SEPARATOR="") AS ?s_t) ' .
            'WHERE { ' .
            '?mirna rdfs:label "hsa-miR-125b-5p" . ' .
            '?p obo:RO_0000057 ?mirna . ' .
            '?p obo:RO_0000057 ?t . ' .
            'FILTER (?t != ?mirna) . ' .
            '?t rdfs:label ?l . ' .
            '?t obo:OMIT_0000109 ?i . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000020 . ' .
            '?p obo:OMIT_0000108 ?m_s ' .
            '} . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000019 . ' .
            '?p obo:OMIT_0000108 ?t_s ' .
            '} ' .
            '} ' .
            'GROUP BY ?l ?i ' .
            'ORDER BY DESC(' . $sortby . ')'.
            ($limit === 'All' ? '' : 'LIMIT ' . $limit . ' OFFSET ' . $offset);


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

        // Holds html markup
        $html = '';

        // Loop through each target
        foreach ($json['results']['bindings'] as $target) {
            if(empty($target['s_m']['value']) && empty($target['s_t']['value'])) {
                continue;
            }

            // Concatenate the target row information
            $html .= '<tr>' .
                '<td><input type="checkbox" name="targets[]" value="' . $target['l']['value'] . '" /></td>' .
                '<td style="font-size: 125%; font-weight: bold">' . $target['l']['value'] . '</td>' .
                '<td>';

            if(!empty($target['s_m']['value'])) {
                $html .= '<a href="http://mirdb.org/cgi-bin/search.cgi?searchType=miRNA&searchBox=' . $mirna . '&full=1" target="_blank">miRDB</a><br/>';
            }
            if(!empty($target['s_t']['value'])) {
                $html .= '<a href="http://www.targetscan.org/cgi-bin/targetscan/vert_70/targetscan.cgi?species=Human&gid=&mir_sc=&mir_c=&mir_nc=&mirg=' . str_replace('hsa-', '', $mirna) . '" target="_blank">TargetScan</a><br/>';
            }
            //if(strpos($target['types']['value'], 'http://purl.obolibrary.org/obo/OMIT_0000021')) {
            //    $html .= '<a href="http://www.microrna.org/microrna/getTargets.do?matureName=' . $mirna . '&organism=9606" target="_blank">microRNA.org</a><br/>';
            //}

            $html .= '</td><td>' .
                '<a href="http://www.ncbi.nlm.nih.gov/pubmed?LinkName=gene_pubmed&from_uid=' . $target['i']['value'] . '" target="_blank">All</a><br/>' .
                '</td><td>' .
                '<a href="http://amigo.geneontology.org/amigo/medial_search?q=' . $target['l']['value'] . '" target="_blank">' . $target['l']['value'] . '</a><br/>' .
                '<a href="http://amigo.geneontology.org/amigo/medial_search?q=' . $mirna . '" target="_blank">' . $mirna . '</a><br/>' .
                '</td></tr>';
        }

        // Echo the results
        echo json_encode(array(
            'success' => true,
            'mirna' => $mirna,
            'term' => $term,
            'page' => $page,
            'page_count' => $page_count,
            'target_count' => $count,
            'html' => $html
        ));
        exit;
    }
    // Else if all targets are requested
    else if ($type === 'all-targets') {
        // Build the query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX owl: <http://www.w3.org/2002/07/owl#> ' .
            'SELECT ?l ?i (GROUP_CONCAT(IF(BOUND(?m_s), ?m_s, ""); SEPARATOR="") AS ?s_m) (GROUP_CONCAT(IF(BOUND(?t_s), ?t_s, ""); SEPARATOR="") AS ?s_t) ' .
            'WHERE { ' .
            '?mirna rdfs:label "hsa-miR-125b-5p" . ' .
            '?p obo:RO_0000057 ?mirna . ' .
            '?p obo:RO_0000057 ?t . ' .
            'FILTER (?t != ?mirna) . ' .
            '?t rdfs:label ?l . ' .
            '?t obo:OMIT_0000109 ?i . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000020 . ' .
            '?p obo:OMIT_0000108 ?m_s ' .
            '} . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000019 . ' .
            '?p obo:OMIT_0000108 ?t_s ' .
            '} ' .
            '} ' .
            'GROUP BY ?l ?i';

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

        // Holds the target strings
        $targets = [];

        // Loop through each target
        foreach ($json['results']['bindings'] as $target) {
            // Add the target string to the array
            $targets[] = $target['l']['value'];
        }

        // Echo the target array
        echo json_encode(array('targets' => $targets));
    }
    // Else if file download is requested
    else if ($type === 'download') {
        // If the file format was not supplied
        if (empty($_GET['format'])) {
            header('Location: /error.php');
            exit;
        }

        // Store the format type
        $format = $_GET['format'];

        // Build the query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX owl: <http://www.w3.org/2002/07/owl#> ' .
            'SELECT ?l ?i (GROUP_CONCAT(IF(BOUND(?m_s), ?m_s, ""); SEPARATOR="") AS ?s_m) (GROUP_CONCAT(IF(BOUND(?t_s), ?t_s, ""); SEPARATOR="") AS ?s_t) ' .
            'WHERE { ' .
            '?mirna rdfs:label "hsa-miR-125b-5p" . ' .
            '?p obo:RO_0000057 ?mirna . ' .
            '?p obo:RO_0000057 ?t . ' .
            'FILTER (?t != ?mirna) . ' .
            '?t rdfs:label ?l . ' .
            '?t obo:OMIT_0000109 ?i . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000020 . ' .
            '?p obo:OMIT_0000108 ?m_s ' .
            '} . ' .
            'OPTIONAL { ' .
            '?p rdf:type obo:OMIT_0000019 . ' .
            '?p obo:OMIT_0000108 ?t_s ' .
            '} ' .
            '} ' .
            'GROUP BY ?l ?i';

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

        // If download all targets
        if ($format === 'tsv' || $format === 'csv') {
            // Holds the contents of the file
            $text = '';

            // Loop through all targets
            foreach ($json['results']['bindings'] as $target) {
                // If tab-separated values
                if ($format === 'tsv')
                    // Concatenate the target information
                    $text .= $target['l']['value'] . "\tmiRDB\t\tAll\t\t\t" . $target['l']['value'] . "\r\n\tTargetScan\t" . $mirna . "-Specific\tmRNA\r\n\tmicroRNA.org\r\n";
                // Else if comma-separated values
                else if ($format === 'csv')
                    // Concatenate the target information
                    $text .= $target['l']['value'] . ",\"miRDB\r\nTargetScan\r\nmicroRNA.org\",\"All\r\n" . $mirna . "-Specific\",\"" . $target['l']['value'] . "\r\nmRNA\"\r\n";
            }

            // Save tsv file with .txt extension
            if ($format === 'tsv')
                $format = 'txt';

            // Create the filename
            $filename = 'Query_Results_ for_' . $mirna . '-' . date('Y-m-d') . '.' . $format;
        }
        // Else if download selected targets
        else if ($format === 'txt') {
            // If the selected targets were not supplied
            if (!isset($_GET['selected'])) {
                // Navigate to the error page
                header('Location: /error.php');
                exit;
            }

            // Store the selected targets
            $selected = $_GET['selected'];
            // Holds the contents of the file
            $text = '';

            // If all targets were requested
            if ($selected === 'all') {
                // Loop through all targets
                foreach ($json['results']['bindings'] as $target) {
                    // Concatenate the target label
                    $text .= $target['l']['value'] . "\r\n";
                }
            }
            // Else
            else {
                // Split the selected string into a string array
                $selected = preg_split('/,/', $selected, -1, PREG_SPLIT_NO_EMPTY);
                // Get the selected targets count
                $select_count = count($selected);

                // Loop through each target label
                foreach ($json['results']['bindings'] as $target) {
                    // If the count is zero or the current target is in the selected targets array
                    if ($select_count > 0 && in_array($target['l']['value'], $selected, true)) {
                        // Concatenate the target label
                        $text .= $target['l']['value'] . "\r\n";
                    }
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
    }
}
// Catch
catch(Exception $ex) {
    // Log the error
    error_log($ex);

    // Inform the user that the store is unavailable
    echo $ex;
    exit;
}

?>