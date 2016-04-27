<?php

// Try
try {
    // Turn off error reporting
    error_reporting(0);

    // Set the date timezone
    date_default_timezone_set('America/New_York');

    // Set the header content type to json
    // header('Content-Type: application/json');

    // Set the request header options to accept sparql-results+json
    $options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));

    // Create the stream context
    $stream_context = stream_context_create($options);

    // Host url
    $host = 'http://localhost:3030/OmniStore/query?query=';

    // Get post parameters
    $mirna = $_POST['mirna'];
    $mesh = $_POST['mesh'];
    $page = $_POST['page'];
    $rows = $_POST['rows'];
    $sort_dir = $_POST['sort_dir'];
    $sort_col = $_POST['sort_col'];
    $selected = json_decode($_POST['selected']);
    $validation_filter = $_POST['validation_filter'];
    $database_filter = json_decode($_POST['database_filter']);
    $pubmed_filter = $_POST['pubmed_filter'];
	$mirna_pubmed_filter = $_POST['mirna_pubmed_filter'];

	// Show/Hide column flags 
    $database_count = count($database_filter);
    $show_mirdb = $database_count === 0 || in_array('mirdb', $database_filter);
    $show_targetscan = $database_count === 0 || in_array('targetscan', $database_filter);
    $show_miranda = $database_count === 0 || in_array('miranda', $database_filter);
    $show_mirtarbase = $database_count === 0 || in_array('mirtarbase', $database_filter);

	// Invalid score based on sort direction
    $invalid_score = $sort_dir == 'DESC' ? -9999 : 9999;
	// Use min or max function in query base on sort direction
    $minmax = $sort_dir == 'DESC' ? 'MAX' : 'MIN';
	// CSS class based on sort direction
    $sort_class = $sort_dir == 'DESC' ? ' sorting_desc' : ' sorting_asc';

	// Having flags based on various filters
    $having = [];
	if ($pubmed_filter == 'has') $having[] = '?pubmed_ids != ""';
	else if ($pubmed_filter == 'no') $having[] = '?pubmed_ids = ""';
    if ($database_count > 0 && $show_mirdb) $having[] = '?mirdb_score != ' . $invalid_score;
    if ($database_count > 0 && $show_targetscan) $having[] = '?targetscan_score != ' . $invalid_score;
    if ($database_count > 0 && $show_miranda) $having[] = '?miranda_score != ' . $invalid_score;
    if ($database_count > 0 && $show_mirtarbase && $validation_filter == 'all') $having[] = '?mirtarbase_id != ""';
    if ($validation_filter == 'predicted') $having[] = '?mirtarbase_id = ""';
    if ($validation_filter == 'validated') $having[] = '?mirtarbase_id != ""';
    $having = count($having) == 0 ? '' : 'HAVING(' . implode(' && ', $having) . ') ';

    // Build the query string
    $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
        'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
        'PREFIX oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> ' .
        'SELECT ?gene_symbol ?gene_name  ' .
        '(MAX(COALESCE(?go_count, 0)) AS ?amigo_count) ' .
        ($show_mirdb ? '(' . $minmax . '(COALESCE(?mdb_score, ' . $invalid_score . ')) AS ?mirdb_score) ' : '') .
        ($show_targetscan ? '(' . $minmax . '(COALESCE(?ts_score, ' . $invalid_score . ')) AS ?targetscan_score) ' : '') .
        ($show_miranda ? '(' . $minmax . '(COALESCE(ABS(?mrnd_score), ' . $invalid_score . ')) AS ?miranda_score) ' : '') .
        '(GROUP_CONCAT(DISTINCT COALESCE(?mtb_id, ""); SEPARATOR="") AS ?mirtarbase_id) ' .
        '(GROUP_CONCAT(DISTINCT COALESCE(?pubmed_id, ""); SEPARATOR=",") AS ?pubmed_ids) ' .
        'WHERE { ' .
        '?mirna rdfs:label "' . $mirna . '" . ' .
        '?prediction obo:OMIT_0000159 ?mirna . ' .
        '?prediction obo:OMIT_0000160 ?target . ' .
        '?target rdfs:label ?gene_symbol . ' .
        '?target rdfs:comment ?gene_name . ' .
        'OPTIONAL { ' .
        '?target obo:OMIT_0000169 ?go_count ' .
        '} ' .
        ($show_targetscan ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000019 . ' .
            '?prediction obo:OMIT_0000108 ?ts_score ' .
            '} ' : '') .
        ($show_mirdb ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000020 . ' .
            '?prediction obo:OMIT_0000108 ?mdb_score ' .
            '} ' : '') .
        ($show_miranda ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000021 . ' .
            '?prediction obo:OMIT_0000108 ?mrnd_score ' .
            '} ' : '') .
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000174 . ' .
            '?prediction oboInOwl:hasDbXref ?mtb_id ' .
            '} ' .
        'OPTIONAL { ' .
        ($mirna_pubmed_filter == 'true' ? '?mirna obo:OMIT_0000151 ?pubmed_id . ' : '') .
        '?target obo:OMIT_0000151 ?pubmed_id . ' .
        (!empty($mesh) ?
            '?mesh_term rdfs:label "' . $mesh . '" . ' .
            '?child (rdfs:subClassOf)* ?mesh_term . ' .
            '?child obo:OMIT_0000151 ?pubmed_id ' : '') .
        '} ' .
        '} ' .
        'GROUP BY ?gene_symbol ?gene_name ' .
        $having;

    // Build the query url
    $url = $host . urlencode($query);

    // Check for apache jena server failure
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
        exit;
    }

    // Check for json decode failure
    if (($json = json_decode($json, true)) === null) {
        echo json_encode(array('success' => false, 'error' => 'json decode error'));
        exit;
    }

	// Check for empty result set
    if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
        echo json_encode(array('success' => false, 'error' => 'No results found'));
        exit;
    }

	// Build the list of gene symbols
    $targets = [];
    foreach ($json['results']['bindings'] as $item) {
        $targets[] = $item['gene_symbol']['value'];
    }

	// Pagination values
    $total = count($targets);
    $rows = $rows == 'all' ? $total : $rows;
    $pages = ceil($total / $rows);
    $page = min($page, $pages);

	// Sorting and limit values
    $order_by = 'ORDER BY ' . $sort_dir . '(?' . $sort_col . ') ';
    $offset = $rows == 'all' ? '' : 'OFFSET ' . (($page - 1) * $rows) . ' ';
    $limit = $rows == 'all' ? '' : 'LIMIT ' . $rows . ' ';

    // Build the query string
    $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
        'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
        'PREFIX oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> ' .
        'SELECT ?gene_symbol ?gene_name ' .
        '(MAX(COALESCE(?go_count, 0)) AS ?amigo_count) ' .
        ($show_mirdb ? '(' . $minmax . '(COALESCE(?mdb_score, ' . $invalid_score . ')) AS ?mirdb_score) ' : '') .
        ($show_targetscan ? '(' . $minmax . '(COALESCE(?ts_score, ' . $invalid_score . ')) AS ?targetscan_score) ' : '') .
        ($show_miranda ? '(' . $minmax . '(COALESCE(ABS(?mrnd_score), ' . $invalid_score . ')) AS ?miranda_score) ' : '') .
        '(GROUP_CONCAT(DISTINCT COALESCE(?mtb_id, ""); SEPARATOR="") AS ?mirtarbase_id) ' .
        '(GROUP_CONCAT(DISTINCT COALESCE(?pubmed_id, ""); SEPARATOR=",") AS ?pubmed_ids) ' .
        'WHERE { ' .
        '?mirna rdfs:label "' . $mirna . '" . ' .
        '?prediction obo:OMIT_0000159 ?mirna . ' .
        '?prediction obo:OMIT_0000160 ?target . ' .
        '?target rdfs:label ?gene_symbol . ' .
        '?target rdfs:comment ?gene_name . ' .
        'OPTIONAL { ' .
        '?target obo:OMIT_0000169 ?go_count ' .
        '} ' .
        ($show_targetscan ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000019 . ' .
            '?prediction obo:OMIT_0000108 ?ts_score ' .
            '} ' : '') .
        ($show_mirdb ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000020 . ' .
            '?prediction obo:OMIT_0000108 ?mdb_score ' .
            '} ' : '') .
        ($show_miranda ?
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000021 . ' .
            '?prediction obo:OMIT_0000108 ?mrnd_score ' .
            '} ' : '') .
            'OPTIONAL { ' .
            '?prediction rdf:type obo:OMIT_0000174 . ' .
            '?prediction oboInOwl:hasDbXref ?mtb_id ' .
            '} ' .
        'OPTIONAL { ' .
        ($mirna_pubmed_filter == 'true' ? '?mirna obo:OMIT_0000151 ?pubmed_id . ' : '') .
        '?target obo:OMIT_0000151 ?pubmed_id . ' .
        (!empty($mesh) ?
            '?mesh_term rdfs:label "' . $mesh . '" . ' .
            '?child (rdfs:subClassOf)* ?mesh_term . ' .
            '?child obo:OMIT_0000151 ?pubmed_id ' : '') .
        '} ' .
        '} ' .
        'GROUP BY ?gene_symbol ?gene_name ' .
        $having . $order_by . $offset . $limit;

    // Build the query url
    $url = $host . urlencode($query);

    // Check for apache jena server failure
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
        exit;
    }

    // Check for json decode failure
    if (($json = json_decode($json, true)) === null) {
        echo json_encode(array('success' => false, 'error' => 'json decode error'));
        exit;
    }

	// Check for empty result set
    if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
        echo json_encode(array('success' => false, 'error' => 'No results found'));
        exit;
    }

	// Generate the table html code
    ob_start();
    include('table.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Echo the results
    echo json_encode(array(
        'success' => true,
        'html' => $html,
        'page' => $page,
        'pages' => $pages,
        'total' => $total,
        'targets' => $targets
    ));
    exit;
}
// Catch
catch (Exception $ex) {
    // Log the error
    error_log($ex);

    // Exception
    echo json_encode(array('success' => false, 'error' => 'Exception:' . $ex));
    exit;
}
