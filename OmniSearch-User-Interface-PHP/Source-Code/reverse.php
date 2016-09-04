<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $options = array('http' => array('method' => "GET", 'header' => "Accept: application/sparql-results+json\r\n"));
    $stream_context = stream_context_create($options);
    $host = 'http://localhost:3030/OmniStore/query?query=';

    if(isset($_POST['query'])) {
        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX obo: <http://purl.obolibrary.org/obo/> PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> SELECT ?label WHERE { ?child rdfs:subClassOf obo:NCRO_0000025 . ?child rdfs:label ?label FILTER STRSTARTS(UCASE(?label), UCASE("'. $_POST['query'] .'")) } ORDER BY ?label';

        $url = $host . urlencode($query);

        if (($json = file_get_contents($url, false, $stream_context)) === false) {
            echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
            exit;
        }

        if (($json = json_decode($json, true)) === null) {
            echo json_encode(array('success' => false, 'error' => 'json decode error'));
            exit;
        }

        if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
            echo json_encode(array('success' => false, 'error' => 'No Results Found'));
            exit;
        }

        $i = 0;
        $html = '';

        foreach ($json['results']['bindings'] as $item) {
            $html .= '<p tabindex="' . $i++ . '">' . $item['label']['value'] . '</p>';
        }

        echo json_encode(array(
            'success' => true,
            'html' => $html
        ));
        exit;
    }
    else if(isset($_POST['gene'])) {
        $gene = $_POST['gene'];
        $page = $_POST['page'];
        $rows = $_POST['rows'];

        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> PREFIX obo: <http://purl.obolibrary.org/obo/> PREFIX oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> SELECT ?mirna_label (MAX(COALESCE(?mdb_score, 0)) AS ?mirdb_score) (MAX(COALESCE(?ts_score, 0)) AS ?targetscan_score) (MAX(COALESCE(ABS(?mrnd_score), 0)) AS ?miranda_score) (GROUP_CONCAT(COALESCE(?mtb_id, ""); SEPARATOR="") AS ?mirtarbase_id) WHERE { ?target rdfs:label "' . $gene . '" . ?prediction obo:OMIT_0000160 ?target . ?prediction obo:OMIT_0000159 ?mirna . ?mirna rdfs:label ?mirna_label . OPTIONAL { ?prediction rdf:type obo:OMIT_0000019 . ?prediction obo:OMIT_0000108 ?ts_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000020 . ?prediction obo:OMIT_0000108 ?mdb_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000021 . ?prediction obo:OMIT_0000108 ?mrnd_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000174 . ?prediction oboInOwl:hasDbXref ?mtb_id } } GROUP BY ?mirna_label';

        $url = $host . urlencode($query);

        if (($json = file_get_contents($url, false, $stream_context)) === false) {
            echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
            exit;
        }

        if (($json = json_decode($json, true)) === null) {
            echo json_encode(array('success' => false, 'error' => 'json decode error'));
            exit;
        }

        if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
            echo json_encode(array('success' => false, 'error' => $error));
            exit;
        }

        $total = count($json['results']['bindings']);
        $rows = $rows == 'all' ? $total : $rows;
        $pages = ceil($total / $rows);
        $page = min($page, $pages);

        $offset = $rows == 'all' ? '' : 'OFFSET ' . (($page - 1) * $rows) . ' ';
        $limit = $rows == 'all' ? '' : 'LIMIT ' . $rows . ' ';

        $query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> PREFIX obo: <http://purl.obolibrary.org/obo/> PREFIX oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> SELECT ?mirna_label (MAX(COALESCE(?mdb_score, 0)) AS ?mirdb_score) (MAX(COALESCE(?ts_score, 0)) AS ?targetscan_score) (MAX(COALESCE(ABS(?mrnd_score), 0)) AS ?miranda_score) (GROUP_CONCAT(COALESCE(?mtb_id, ""); SEPARATOR="") AS ?mirtarbase_id) WHERE { ?target rdfs:label "' . $gene . '" . ?prediction obo:OMIT_0000160 ?target . ?prediction obo:OMIT_0000159 ?mirna . ?mirna rdfs:label ?mirna_label . OPTIONAL { ?prediction rdf:type obo:OMIT_0000019 . ?prediction obo:OMIT_0000108 ?ts_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000020 . ?prediction obo:OMIT_0000108 ?mdb_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000021 . ?prediction obo:OMIT_0000108 ?mrnd_score } OPTIONAL { ?prediction rdf:type obo:OMIT_0000174 . ?prediction oboInOwl:hasDbXref ?mtb_id } } GROUP BY ?mirna_label ORDER BY ASC(?mirna_label) ' . $offset . $limit;

        $url = $host . urlencode($query);

        if (($json = file_get_contents($url, false, $stream_context)) === false) {
            echo json_encode(array('success' => false, 'error' => 'Server Timeout / Host Unreachable'));
            exit;
        }

        if (($json = json_decode($json, true)) === null) {
            echo json_encode(array('success' => false, 'error' => 'json decode error'));
            exit;
        }

        if (count($json['results']['bindings']) == 0 || empty($json['results']['bindings'][0])) {
            echo json_encode(array('success' => false, 'error' => $error));
            exit;
        }

        $i = ($page - 1) * $rows;
        $html = '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>All</th>
                            <th>microRNA Name</th>
                            <th>miRDB</th>
                            <th>TargetScan</th>
                            <th>miRanda</th>
                            <th>miRTarBase</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($json['results']['bindings'] as $item) {
            $html .= '<tr>' .
                '<td><p>' . ++$i . '</p></td>' . 
                '<td><p>' . $item['mirna_label']['value'] . '</p></td>' .
                '<td><p>' . ($item['mirdb_score']['value'] == 0 ? '-' : round($item['mirdb_score']['value'])) . '</p></td>' .
                '<td><p>' . ($item['targetscan_score']['value'] == 0 ? '-' : $item['targetscan_score']['value']) . '</p></td>' .
                '<td><p>' . ($item['miranda_score']['value'] == 0 ? '-' : $item['miranda_score']['value']) . '</p></td>' .
                '<td><p>' . ($item['mirtarbase_id']['value'] == '' ? '-' : $item['mirtarbase_id']['value']) . '</p></td>' .
                '</tr>';
        }


        $html .= '</tbody>
                </table>';

        echo json_encode(array(
            'success' => true,
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
            'html' => $html
        ));
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>OmniSearch</title>

    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/main.css"/>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/ui/" style="padding: 5px">
				<img src="/ui/images/logo.png" class="img-responsive" style="max-height: 40px"/>
			</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/ui/about.php">About</a></li>
                <li><a href="/ui/help.php">Help</a></li>
                <li><a href="/" target="_blank">Wiki/Feedback</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="header">
    <form id="search_form" autocomplete="off">
        <h2>Search for microRNA</h2>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="searchbox">
                    <label for="gene">
                        Enter a Target Gene
                        <span class="glyphicon glyphicon-question-sign pull-right" style="color: yellow" data-toggle="tooltip" data-placement="left" title="Begin typing a complete gene name or part of such a name. Or simply press the down-arrow key without typing anything."></span>
                    </label>
                    <input id="gene" type="text" class="form-control" required autofocus/>
                    <div></div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-default btn-block">
                    <span class="glyphicon glyphicon-search"></span><span> Search</span>
                </button>
            </div>
        </div>
    </form>
</div>
<div id="wrapper">
    <div id="error_panel"></div>
    <div id="content">
        <div id="page_controls" class="row">
            <div class="col-md-2 col-sm-2">
                <label for="rows_select">Rows per page</label>
                <select id="rows_select" class="form-control" style="width: 100%" autocomplete="off">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                    <option value="all">All</option>
                </select>
            </div>
            <div class="col-md-8 col-sm-8" style="text-align: center">
                <label><span id="total_span"></span></label>
                <nav>
                    <ul class="pagination">
                        <li><button id="first_btn" type="button" onclick="first()">&laquo;</button></li>
                        <li><button id="prev_btn" type="button"  onclick="prev()">&lsaquo;</button></li>
                        <li><label>Page <span id="page_span">0</span> of <span id="pages_span">0</span></label></li>
                        <li><button id="next_btn" type="button"  onclick="next()">&rsaquo;</button></li>
                        <li><button id="last_btn" type="button"  onclick="last()">&raquo;</button></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-2 col-sm-2">
                <form id="goto_page_form" autocomplete="off">
                    <label for="page_input">Go to Page</label>
                    <div class="input-group">
                        <input id="page_input" type="text" class="form-control" required/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Go</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div id="table_div" class="table-responsive">
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
var page = 1;
var pages = 1;
var total = 0;
var rows = 100;
var jqxhr = null;

$('body').tooltip({
    selector: '[data-toggle=tooltip]'
});

$(document).on('mousedown', function () {
    $('.searchbox > div').hide();
    if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}
});

function search() {
    var gene = $('#gene').val();

    $('.searchbox > div').hide();
    if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}

    $('input, button, select').prop('disabled', true);

    $.post('reverse.php', {
            gene: gene,
            page: page,
            rows: rows
        })
        .done(function (json) {
			if(json.length == 0) {
                $('#error_panel').html('<h2>Server Timeout</h2>').show();
				return;
			}
            json = JSON.parse(json);
            if (json.success) {
                total = json.total;
                pages = json.pages;
                $('#total_span').text(json.total + ' Total miRNA');
                $('#pages_span').text(json.pages);
                $('#page_span').text(json.page);
                $('#table_div').html(json.html);
                $('#error_panel').hide();
                $('#content').show();
            }
            else {
                $('#content').hide();
                $('#error_panel').html('<h2>' + json.error + '</h2>').show();
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $('#content').hide();
			$('#error_panel').html('<h2>Server Timeout</h2>').show();
        })
        .always(function () {
            $('input, button, select').prop('disabled', false);
        });
}

$('#search_form').on('submit', function (e) {
    e.preventDefault();
    $('#error_panel').html('<h2>Searching...</h2>').show();
    search();
});

$('#rows_select').on('change', function () {
    rows = $(this).val();
    search();
});

$('#goto_page_form').on('submit', function (e) {
    e.preventDefault();

    var input = $(this).find('input[type=text]');
    page = parseInt(input.val(), 10);
    input.val('');
    search();
});

$('#goto_page_form').find('input[type=text]').on('keydown', function (e) {
    var key = (96 <= e.keyCode && e.keyCode <= 105) ? e.keyCode - 48 : e.keyCode;
    if (key != 8 && key != 13 && (key < 48 || key > 57))
        return false;

    if (key != 8 && key != 13) {
        var n = parseInt($(this).val() + String.fromCharCode(key));
        if (n < 1 || n > pages)
            return false;
    }
});

function first() {
    page = 1;
    search();
}

function prev() {
    page = Math.max(1, page - 1);
    search();
}

function next() {
    page = Math.min(pages, page + 1);
    search();
}

function last() {
    page = pages;
    search();
}

$('.searchbox').on('mousedown', 'div', function (e) {
    e.stopPropagation();
});

$('.searchbox').on('blur', function() {
	if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}
});

$('.searchbox').on('click', 'div > ul > li', function () {
    $(this).parent().toggleClass('collapsed').find('ul').toggle();
});

$('.searchbox > div')
    .on('keydown', function (e) {
        e.preventDefault();

        var p = $(this).find('p:focus');

        if (e.which == 38) {
            var prev = p.prev('p');
            if (prev.length) {
                prev.focus();
            }
        }
        else if (e.which == 40) {
            var next = p.next('p');
            if (next.length) {
                next.focus();
            }
        }
    })
    .on('keydown', 'p', function (e) {
        if (e.which == 9 || e.which == 13) {
            $(this).closest('div').hide();
            $(this).closest('div').prev().val($(this).text()).focus();
            if (e.which == 9)
                $(this).closest('div').parent().parent().next().find('input, button').focus();
        }
    })
    .on('mousemove', 'p', function () {
        $(this).focus();
    })
    .on('mousedown', 'p', function () {
        $(this).closest('div').prev().val($(this).text());
        $(this).closest('div').hide();
    });

$('#gene')
    .on('input', function () {
        query(false);
    })
    .on('keydown', function (e) {
        if (e.which == 40) {
            if ($(this).next().is(':visible')) {
                $(this).next().find('p:first').focus();
            }
            else {
                query(true);
            }
            return false;
        }
    });

function query(focus) {
    if (focus)
        $('#gene').prop('disabled', true);

    $('#gene').next().html('<h5>Searching...</h5>').show();

    if (jqxhr) {
        jqxhr.abort();
		jqxhr = null;
	}

    jqxhr = $.post('reverse.php', {
            query: $('#gene').val()
        })
        .done(function (json) {
            if(json.length == 0) {
                $('#error_panel').html('<h2>Server Timeout</h2>').show();
				return;
			}
            json = JSON.parse(json);
            if (json.success && jqxhr) {
                $('#gene').next().html(json.html).show();
                $('#gene').next().find('p').on('click', function () {
                    $('#gene').val($(this).text());
                });
                if (focus) {
                    $('#gene').next().find('p:first').focus();
                }
            }
            else {
                $('#gene').next().html('<h5>' + json.error + '</h5>').show();
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (errorThrown != 'abort')
                alert(textStatus + ':' + errorThrown);
        })
        .always(function () {
            $('#gene').prop('disabled', false);
        });
    }
</script>
</body>
</html>