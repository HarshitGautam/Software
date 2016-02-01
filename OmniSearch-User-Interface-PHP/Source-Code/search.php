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
    $type = $_GET['type'];
    $mirna = $_GET['mirna'];
    $term = $_GET['term'];
    $sort_by = $_GET['sort_by'];
    $predicted_by = $_GET['predicted_by'];
    $publication_filter = $_GET['publication_filter'];
    $page = $_GET['page'];
    $limit = $_GET['limit'];

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

    if ($type === 'select_all') {
        // Holds the targets
        $targets = [];

        // Loop through each target
        foreach ($json['results']['bindings'] as $target) {
            // Add the target gene symbol to the array
            $targets[] = $target['gene_symbol']['value'];
        }

        // Set the header content type to json
        header('Content-Type: application/json');

        // Return the targets array
        echo json_encode(array('targets' => $targets));
        exit;
    }

    // Store the target count
    $count = count($json['results']['bindings']);

    // If the count is zero
    if ($count == 0 || $json['results']['bindings'][0] == null) {
        // Set the header content type to json
        header('Content-Type: application/json');

        // Return false to the ajax request
        echo json_encode(array('result' => false));
        exit;
    }

    // If requesting all pages
    if ($limit === 'all') {
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

    // Concatenate the limit and offset to the query
    $query .= ($limit === 'all' ? '' : 'LIMIT ' . $limit . ' OFFSET ' . $offset . ' ');

    // Rebuild the query url
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
    $i = $offset + 1;

    // Loop through each target
    foreach ($json['results']['bindings'] as $target) {
        // Concatenate the target row information
        $html .= '<tr>' .
            '<td><input type="checkbox" name="targets[]" value="' . $target['gene_symbol']['value'] . '" />&nbsp;<p style="display: inline-block">' . $i++ . '</p></td>' .
            '<td style="font-size: 125%; font-weight: bold">' . $target['gene_symbol']['value'] . '</td>' .
            '<td>';

        if (isset($target['mirdb_score']['value']) && $target['mirdb_score']['value'] != 0) {
            $html .= '<a href="http://mirdb.org/cgi-bin/search.cgi?searchType=miRNA&searchBox=' . $mirna . '&full=1" target="_blank">miRDB</a><br/>';
        }
        if (isset($target['targetscan_score']['value']) && $target['targetscan_score']['value'] != 0) {
            $html .= '<a href="http://www.targetscan.org/cgi-bin/targetscan/vert_70/targetscan.cgi?species=Human&gid=&mir_sc=&mir_c=&mir_nc=&mirg=' . str_replace('hsa-', '', $mirna) . '" target="_blank">TargetScan</a><br/>';
        }
        if (isset($target['miranda_score']['value']) && $target['miranda_score']['value'] != 0) {
            $html .= '<a href="http://www.microrna.org/microrna/getTargets.do?matureName=' . $mirna . '&organism=9606" target="_blank">microRNA.org</a><br/>';
        }

        $html .= '</td><td>';

        if (empty($target['pubmed_ids']['value'])) {
<<<<<<< HEAD
            $html .= '<span style="margin: 0">' . $target['gene_symbol']['value'] . ' (0)</span>';
        } else {
            $pubmed_count = count(explode(',', $target['pubmed_ids']['value']));
            $html .= '<a href="http://www.ncbi.nlm.nih.gov/pubmed/' . $target['pubmed_ids']['value'] . '" target="_blank">' . $target['gene_symbol']['value'] . ' (' . $pubmed_count . ')</a>';
        }

        $html .= '</td><td>' .
            '<a href="http://amigo.geneontology.org/amigo/search/annotation?fq=taxon_label:&quot;Homo sapiens&quot;&q=&quot;' .$target['gene_symbol']['value'] . '&quot;' . '" target="_blank">' . $target['gene_symbol']['value'] . '</a>' .
=======
            $html .= '<p>' . $target['gene_symbol']['value'] . ' (0)</p><br/>';
        } else {
            $pubmed_count = count(explode(',', $target['pubmed_ids']['value']));
            $html .= '<a href="http://www.ncbi.nlm.nih.gov/pubmed/' . $target['pubmed_ids']['value'] . '" target="_blank">' . $target['gene_symbol']['value'] . ' (' . $pubmed_count . ')</a><br/>';
        }

        $html .= '</td><td>' .
            '<a href="http://amigo.geneontology.org/amigo/medial_search?q=' . $target['gene_symbol']['value'] . '" target="_blank">' . $target['gene_symbol']['value'] . '</a><br/>' .
            '<a href="http://amigo.geneontology.org/amigo/medial_search?q=' . $mirna . '" target="_blank">' . $mirna . '</a><br/>' .
>>>>>>> origin/master
            '</td></tr>';
    }

    // Set the header content type to json
    header('Content-Type: application/json');

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

} // Catch
catch (Exception $ex) {
    // Log the error
    error_log($ex);

    // Navigate to error page
    header('Location: /error.php');
    exit;
}
