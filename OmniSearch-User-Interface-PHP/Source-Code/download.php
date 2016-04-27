<?php
// Start the session
session_start();

// Try
try {
    // Turn off error reporting
    error_reporting(0);

    // Set the date timezone
    date_default_timezone_set('America/New_York');

    if(isset($_POST['format']))
        $format = $_POST['format'];
    else
        $format = $_GET['format'];

    if($format == 'selected') {
        $_SESSION['selected'] = json_decode($_POST['selected']);

        echo json_encode(array('success' => true));
        exit;
    }
    else if($format == 'txt') {
        $text = '';

        foreach($_SESSION['selected'] as $symbol) {
            $text .= $symbol . "\r\n";
        }

        // Create the filename
        $filename = 'Target_List_ for_' . $_GET['mirna'] . '-' . date('Y-m-d') . '.txt';

        // Set the Response Header properties and echo the file contents
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($text));
        echo $text;
        exit;
    }
    else if($format == 'tsv' || $format == 'csv') {
        // Set the request header options to accept sparql-results+json
        $options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));

        // Create the stream context
        $stream_context = stream_context_create($options);

        // Host url
        $host = 'http://localhost:3030/OmniStore/query?query=';

        // Get post parameters
        $mirna = $_GET['mirna'];
        $mesh = $_GET['mesh'];
        $sort_dir = $_GET['sort_dir'];
        $sort_col = $_GET['sort_col'];
        $validation_filter = $_GET['validation_filter'];
        $database_filter = json_decode($_GET['database_filter']);
        $pubmed_filter = $_GET['pubmed_filter'];

        $database_count = count($database_filter);
        $show_mirdb = $database_count === 0 || in_array('mirdb', $database_filter);
        $show_targetscan = $database_count === 0 || in_array('targetscan', $database_filter);
        $show_miranda = $database_count === 0 || in_array('miranda', $database_filter);
        $show_mirtarbase = $database_count === 0 || in_array('mirtarbase', $database_filter);

        $having = [];
        if ($pubmed_filter == 'has') $having[] = '?pubmed_ids != ""';
        else if ($pubmed_filter == 'no') $having[] = '?pubmed_ids = ""';
        if ($database_count > 0 && $show_mirdb) $having[] = '?mirdb_score != -9999';
        if ($database_count > 0 && $show_targetscan) $having[] = '?targetscan_score != -9999';
        if ($database_count > 0 && $show_miranda) $having[] = '?miranda_score != -9999';
        if ($database_count > 0 && $show_mirtarbase && $validation_filter == 'all') $having[] = '?mirtarbase_id != ""';
        if ($validation_filter == 'predicted') $having[] = '?mirtarbase_id = ""';
        if ($validation_filter == 'validated') $having[] = '?mirtarbase_id != ""';

        $having = count($having) == 0 ? '' : 'HAVING(' . implode(' && ', $having) . ') ';
        $order_by = 'ORDER BY ' . $sort_dir . '(?' . $sort_col . ') ';

        // Build the query string
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> ' .
            'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ' .
            'PREFIX obo: <http://purl.obolibrary.org/obo/> ' .
            'PREFIX oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> ' .
            'SELECT ?gene_symbol ?gene_name ' .
            '(MAX(COALESCE(?go_count, 0)) AS ?amigo_count) ' .
            ($show_mirdb ? '(MAX(COALESCE(?mdb_score, -9999)) AS ?mirdb_score) ' : '') .
            ($show_targetscan ? '(MAX(COALESCE(?ts_score, -9999)) AS ?targetscan_score) ' : '') .
            ($show_miranda ? '(MAX(COALESCE(ABS(?mrnd_score), -9999)) AS ?miranda_score) ' : '') .
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
            '?mirna obo:OMIT_0000151 ?pubmed_id . ' .
            '?target obo:OMIT_0000151 ?pubmed_id . ' .
            (!empty($mesh) ?
                '?mesh_term rdfs:label "' . $mesh . '" . ' .
                '?child (rdfs:subClassOf)* ?mesh_term . ' .
                '?child obo:OMIT_0000151 ?pubmed_id ' : '') .
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
            echo json_encode(array('success' => false, 'error' => 'No results found'));
            exit;
        }

        // Holds file contents
        $text = '';
        // Holds filename
        $filename = '';

        if ($format === 'csv') {
            // Write description comment
            $text .= "#miRNA,gene_symbol,gene_name,miRDB_score,TargetScan_score,miRanda_score,miRTarBase_id,pubmed_ids\r\n";

            foreach($json['results']['bindings'] as $item) {
                $mdb_score = ($item['mirdb_score']['value'] == -9999 ? '-' : $item['mirdb_score']['value']);
                $ts_score = ($item['targetscan_score']['value'] == -9999 ? '-' : $item['targetscan_score']['value']);
                $mrnd_score = ($item['miranda_score']['value'] == -9999 ? '-' : $item['miranda_score']['value']);
                $mtb_id = ($item['mirtarbase_id']['value'] == "" ? '-' : $item['mirtarbase_id']['value']);
                $pubmed_ids = ($item['pubmed_ids']['value'] == "" ? '-' : $item['pubmed_ids']['value']);

                // Concatenate the target information
                $text .= $mirna . "," . $item['gene_symbol']['value'] . ",\"" . $item['gene_name']['value'] . "\"," . $mdb_score . "," . $ts_score . "," . $mrnd_score . "," . $mtb_id . "," . $pubmed_ids . "\r\n";
            }

            // Create the filename
            $filename = 'Query_Results_ for_' . $mirna . '-' . date('Y-m-d') . '.' . $format;
        }
        else if ($format === 'tsv') {
            // Write description comment
            $text .= "#miRNA\tgene_symbol\tgene_name\tmiRDB_score\tTargetScan_score\tmiRanda_score\tmiRTarBase_id\tpubmed_ids\r\n";

            foreach($json['results']['bindings'] as $item) {
                $mdb_score = ($item['mirdb_score']['value'] == -9999 ? '-' : $item['mirdb_score']['value']);
                $ts_score = ($item['targetscan_score']['value'] == -9999 ? '-' : $item['targetscan_score']['value']);
                $mrnd_score = ($item['miranda_score']['value'] == -9999 ? '-' : $item['miranda_score']['value']);
                $mtb_id = ($item['mirtarbase_id']['value'] == "" ? '-' : $item['mirtarbase_id']['value']);
                $pubmed_ids = ($item['pubmed_ids']['value'] == "" ? '-' : $item['pubmed_ids']['value']);

                // Concatenate the target information
                $text .= $mirna . "\t" . $item['gene_symbol']['value'] . "\t" . $item['gene_name']['value'] . "\t" . $mdb_score . "\t" . $ts_score . "\t" . $mrnd_score . "\t" . $mtb_id . "\t" . $pubmed_ids . "\r\n";
            }

            // Create the filename
            $filename = 'Query_Results_ for_' . $mirna . '-' . date('Y-m-d') . '.txt';
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
        exit;
    }

} // Catch
catch (Exception $ex) {
    // Log the error
    error_log($ex);

    // Return error
    header('HTTP/1.1 500 Internal Server Error');
    exit;
}
