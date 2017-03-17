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
	// $host = 'http://localhost:8890/sparql?query=';
    $host = 'http://localhost:3030/OmniStore/query?query=';

    // Get post parameters
	$gene = $_POST['gene'];
    $mirna = $_POST['mirna'];
    $mesh = $_POST['mesh'];
    $page = $_POST['page'];
    $rows = $_POST['rows'];
    $sort_dir = $_POST['sort_dir'];
    $sort_col = $_POST['sort_col'];
    $selected = json_decode($_POST['selected']);
    $validation_filter = $_POST['validation_filter'];
	$mesh_filter= $_POST['mesh_filter'];
    $database_filter = json_decode($_POST['database_filter']);
    $database_operator = $_POST['database_operator'];
    $pubmed_filter = $_POST['pubmed_filter'];

    $database_count = count($database_filter);
    if($database_count == 0) {
        echo json_encode(array('success' => false, 'error' => 'Select one or more data sources in the filters panel.'));
        exit;
    }

    $show_mirdb = in_array('mirdb', $database_filter);
    $show_targetscan = in_array('targetscan', $database_filter);
    $show_miranda = in_array('miranda', $database_filter);
    $show_mirtarbase = in_array('mirtarbase', $database_filter);

    $invalid_score = $sort_dir == 'DESC' ? -9999 : 9999;
    $minmax = $sort_dir == 'DESC' ? 'MAX' : 'MIN';
    $sort_class = $sort_dir == 'DESC' ? ' sorting_desc' : ' sorting_asc';

    $database_having = [];
    if ($show_mirdb) $database_having[] = '?mirdb_score != ' . $invalid_score;
    if ($show_targetscan) $database_having[] = '?targetscan_score != ' . $invalid_score;
    if ($show_miranda) $database_having[] = '?miranda_score != ' . $invalid_score;
    if ($show_mirtarbase && $validation_filter == 'all') $database_having[] = '?mirtarbase_id != ""';
    $database_operator = $database_operator == 'any' ? ' || ' : ' && ';

    $having = [];
    if ($pubmed_filter == 'has') $having[] = '?pubmed_ids != ""';
    else if ($pubmed_filter == 'no') $having[] = '?pubmed_ids = ""';
    if ($validation_filter == 'predicted') $having[] = '?mirtarbase_id = ""';
    if ($validation_filter == 'validated') $having[] = '?mirtarbase_id != ""';
    if (count($database_having) > 0) $having[] = '(' . implode($database_operator, $database_having) . ')';

    $having = count($having) == 0 ? '' : 'HAVING(' . implode(' && ', $having) . ') ';
    $order_by = 'ORDER BY ' . $sort_dir . '(?' . $sort_col . ') ';

    $default_having = 'HAVING((?mirdb_score != ' . $invalid_score .
        ' || ?targetscan_score != ' . $invalid_score .
        ' || ?miranda_score != ' . $invalid_score .
        ' || ?mirtarbase_id != "")) ';

	$error = $having == $default_having ? '<br/>There are no predicted/validated targets for microRNA ' . $mirna . '. <br/><br/>Visit <a href="http://rnacentral.org/search?q=' . $mirna . '" target="_blank">RNAcentral</a> to search more databases.' : 'No results found.<br/><br/>Add/Remove one or more filters to show results.';
	
    // Build the query string
    $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
		'PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> ' .
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
		 
        '?mirna rdfs:label "' . $mirna . '"^^xsd:string . ' .
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
        '?mirna obo:OMIT_0000151 ?pubmed_id . ' .
        '?target obo:OMIT_0000151 ?pubmed_id . ' .
        ( (!empty($mesh) && ($mesh_filter=='exact') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
            
            '?mesh_term obo:OMIT_0000151 ?pubmed_id ' : '') .
		( (!empty($mesh) && ($mesh_filter=='narrow') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
            '?child rdfs:subClassOf* ?mesh_term .'.
            '?child obo:OMIT_0000151 ?pubmed_id ' : '') .
		( (!empty($mesh) && ($mesh_filter=='broader') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
			
            '?mesh_term rdfs:subClassOf* ?parent .'.
			
            '?parent obo:OMIT_0000151 ?pubmed_id ' : '') .
        '} ' .
        '} ' .
        'GROUP BY ?gene_symbol ?gene_name ' .
        $having . $order_by;

    // Build the query url
    $url = $host . urlencode($query);

    // If the query failed
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        // Server timed out
        echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
        exit;
    }

    // If decoding the json string failed
    if (($json = json_decode($json, true)) === null) {
        // JSON decode failed
        echo json_encode(array('success' => false, 'error' => 'json decode error'));
        exit;
    }
	
    if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
        echo json_encode(array('success' => false, 'error' => $error));
        exit;
    }

    $targets = [];
    foreach ($json['results']['bindings'] as $item) {
        $targets[] = $item['gene_symbol']['value'];
    }

    $total = count($targets);
    $rows = $rows == 'all' ? $total : $rows;
    $pages = ceil($total / $rows);
    $page = min($page, $pages);

    $offset = $rows == 'all' ? '' : 'OFFSET ' . (($page - 1) * $rows) . ' ';
    $limit = $rows == 'all' ? '' : 'LIMIT ' . $rows . ' ';

    // Build the query string
    $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
        'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
		'PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> ' .
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
		
        '?mirna rdfs:label "' . $mirna . '"^^xsd:string . ' .
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
        '?mirna obo:OMIT_0000151 ?pubmed_id . ' .
        '?target obo:OMIT_0000151 ?pubmed_id . ' .
        ( (!empty($mesh) && ($mesh_filter=='exact') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
            
            '?mesh_term obo:OMIT_0000151 ?pubmed_id ' : '') .
			( (!empty($mesh) && ($mesh_filter=='narrow') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
            '?child rdfs:subClassOf* ?mesh_term .'.
            '?child obo:OMIT_0000151 ?pubmed_id ' : '') .
			( (!empty($mesh) && ($mesh_filter=='broader') )?
            '?mesh_term rdfs:label "' . $mesh . '"^^xsd:string . ' .
			
            '?mesh_term rdfs:subClassOf* ?parent .'.
			
            '?parent obo:OMIT_0000151 ?pubmed_id ' : '') .
        '} ' .
        '} ' .
        'GROUP BY ?gene_symbol ?gene_name ' .
        $having . $order_by . $offset . $limit;

    // Build the query url
    $url = $host . urlencode($query);

	
    // If the query failed
    if (($json = file_get_contents($url, false, $stream_context)) === false) {
        // Server timed out
        echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
        exit;
    }

    // If decoding the json string failed
    if (($json = json_decode($json, true)) === null) {
        // JSON decode failed
        echo json_encode(array('success' => false, 'error' => 'json decode error'));
        exit;
    }

    if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
        echo json_encode(array('success' => false, 'error' => $error));
        exit;
    }

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
