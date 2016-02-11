<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>OmniSearch</title>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
<<<<<<< HEAD
    <link rel="stylesheet" href="css/omnisearch.css"/>
=======
    <link rel="stylesheet" href="/css/omnisearch.css"/>
>>>>>>> origin/master

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<<<<<<< HEAD
    <script src="js/omnisearch.js"></script>
=======
    <script src="/js/omnisearch.js"></script>
>>>>>>> origin/master
</head>
<body>
<div style="display: table; margin: 0 auto">
    <table style="width: 100%">
        <tr>
            <td>
<<<<<<< HEAD
                <h4 class="pull-right"><a href="/wiki">OmniSearch Wiki</a></h4>
            </td>
        </tr>
        <tr>
            <td>
                <img src="images/logo-ui.png" class="img-responsive" style="margin: 0 auto; height: 150px"/>
            </td>
        </tr>
    </table>
    <br/>
=======
                <img src="/images/logo.png" class="img-responsive" style="margin: 0; height: 150px"/>
            </td>
            <td>
                <h1 id="header" style="text-align: right">OmniSearch User Interface</h1>
            </td>
        </tr>
    </table>
>>>>>>> origin/master
    <!--
    <br/>
    <p style="text-align: center">All software tools developed in the OmniSearch project can be downloaded here. <a href="https://github.com/OmniSearch/Software" target="_blank">[Executable]</a>&nbsp;&nbsp;<a href="https://github.com/OmniSearch/Software" target="_blank">[Source]</a></p>
    <br/>
    -->
    <form id="search_form">
        <table id="search_controls" class="controls">
            <thead>
            <tr>
                <th style="padding-left: 0">
                    Enter a microRNA
                    <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip"
                          data-placement="left"
                          title="Begin typing a complete microRNA name or part of such a name. Or simply press the down-arrow key without typing anything."></span>
                </th>
                <th>
                    Enter a MeSH Term
                    <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip"
                          data-placement="left"
                          title="Begin typing a complete MeSH Term or part of such a term. Or simply press the down-arrow key without typing anything."></span>
                </th>
                <th>
                    MeSH Term Filter
                    <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip"
                          data-placement="left"
                          title="Selecting Broader/Narrower MeSH Term will result in filtering on BOTH the selected MeSH term PLUS its broader/narrower terms."></span>
                </th>
                <th style="padding-right: 0">
                    &nbsp;
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="padding-left: 0">
                    <div id="mirna_searchbox" class="searchbox">
                        <input id="mirna_searchbox_input" type="text" class="form-control" autocomplete="off" required/>
                        <div id="mirna_searchbox_div"></div>
                    </div>
                </td>
                <td>
                    <div id="term_searchbox" class="searchbox">
                        <input id="term_searchbox_input" type="text" class="form-control" autocomplete="off"/>
                        <div id="term_searchbox_div"></div>
                    </div>
                </td>
                <td>
                    <select id="match_select" class="form-control" autocomplete="off">
                        <option selected>Exact MeSH Term</option>
                        <option>Broader MeSH Term</option>
                        <option>Narrower MeSH Term</option>
                    </select>
                </td>
                <td style="padding-right: 0">
                    <button #id="search_btn" type="submit" class="btn btn-default btn-block" autocomplete="off">Search
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <br/><br/>
    <table id="results_controls" style="display: table; margin: 8px auto">
        <tr>
            <td style="padding-right: 4px">
                <label style="white-space: nowrap">Rows Per Page</label>
            </td>
            <td style="padding-left: 4px">
                <select id="limit_select" class="form-control" autocomplete="off" disabled>
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
            </td>
            <td style="padding-left: 8px">
                <button id="first_btn" type="button" class="btn btn-default"
                        style="margin-bottom: 0; border-right: 0 none; border-top-right-radius: 0; border-bottom-right-radius: 0"
                        autocomplete="off" disabled>&laquo;</button>
            </td>
            <td>
                <button id="prev_btn" type="button" class="btn btn-default" style="margin-bottom: 0; border-radius: 0"
                        autocomplete="off" disabled>&lsaquo;</button>
            </td>
            <td>
                <label class="form-control"
                       style="margin-bottom: 0; border-left: 0 none; border-right: 0 none; border-radius: 0; white-space: nowrap; padding-top: 0">Page
                    <input id="page_txt" type="text" class="form-control" value="0" autocomplete="off" disabled/> of
                    <span id="page_count_lbl">0</span></label>
            </td>
            <td>
                <button id="next_btn" type="button" class="btn btn-default" style="margin-bottom: 0; border-radius: 0"
                        autocomplete="off" disabled>&rsaquo;</button>
            </td>
            <td>
                <button id="last_btn" type="button" class="btn btn-default" class="btn btn-default"
                        style="margin-bottom: 0; border-left: 0 none; border-top-left-radius: 0; border-bottom-left-radius: 0"
                        autocomplete="off" disabled>&raquo;</button>
            </td>
            <td style="padding-left: 8px">
                <div id="download_results" class="download">
                    <button id="download_results_btn" type="button" class="btn btn-default" autocomplete="off" disabled>
                        Download Results
                    </button>
                    <div id="download_results_div">
                        <label><input id="download_all_radio" type="radio" name="amount"/> Download the whole table
                            (<span id="total_count_lbl"></span>)</label><br/>
                        <label>Format: </label>
                        <select id="format" class="form-control">
                            <option value="tsv" selected>Tab-delimited text</option>
                            <option value="csv">CSV format</option>
                        </select><br/><br/>
                        <label><input id="download_selected_radio" type="radio" name="amount"/> Download selected
                            targets (<span id="selected_count_lbl"></span>)</label><br/><br/>
                        <button id="download_btn" type="button" class="btn btn-default pull-right">Download</button>
                    </div>
                </div>
            </td>
            <td style="padding-left: 8px">
                <div id="perform_analysis" class="download">
                    <button id="perform_analysis_btn" type="button" class="btn btn-default" autocomplete="off" disabled>
                        Perform Analysis
                    </button>
                    <div id="perform_analysis_div">
                        <label for="david_tool_select">Select DAVID Tool:</label><br/>
                        <select id="david_tool_select" class="form-control" autocomplete="off">
                            <option value="gene2gene">Gene Functional Classification</option>
                            <option value="term2term">Funtional Annotation Clustering</option>
                            <option value="summary" selected>Functional Annotation Summary</option>
                            <option value="chartReport">Functional Annotation Chart</option>
                            <option value="annotationReport">Functional Annotation Table</option>
                            <option value="list" selected>Show Gene List Names in Batch</option>
                            <option value="geneReport">Gene Report</option>
                            <option value="geneReportFull">Gene Full Report</option>
                        </select><br/><br/>
                        <button id="david_btn" class="btn btn-default pull-right" type="button">DAVID</button>
                    </div>
                </div>
            </td>
            <td style="padding-left: 8px">
                <button id="clear_results_btn" class="btn btn-default" type="button" autocomplete="off" disabled>Clear
                    Results
                </button>
            </td>
        </tr>
    </table>
    <table id="results" width="100%">
        <thead>
        <tr>
            <th width="10%" rowspan="2">
                <!--
                <label><input id="predicted_by_any_database" type="radio" name="predicted_by" value="any" autocomplete="off" checked disabled/>Predicted by any database</label><br/>
                <label><input id="predicted_by_all_databases" type="radio" name="predicted_by" value="all" autocomplete="off" disabled/>Predicted by all databases</label><br/>
                -->
                <label for="select_all_cb">Select All</br>Targets<br/><input id="select_all_cb" type="checkbox"
                                                                             autocomplete="off" disabled/></label>
            </th>
            <th width="20%" rowspan="2">
                Candidate Targets<br/><br/>
                Sort by Score<br/>
                <select id="sort_by_select" class="form-control" autocomplete="off" disabled>
                    <option value="mirdb" selected>miRDB</option>
                    <option value="targetscan">TargetScan</option>
                    <option value="miranda">miRanda</option>
                </select>
            </th>
            <th width="20%" rowspan="2">
                Predicted By<br/><br/>
                Filter<br/>
                <select id="predicted_by_select" name="predicted_by" class="form-control" autocomplete="off" disabled>
                    <option value="any" selected>Any Database</option>
                    <option value="all">All Databases</option>
                </select>
            </th>
            <th width="50%" colspan="2">
                Evidence Filter &nbsp;
                <select id="publication_filter_select" name="publication_filter" class="form-control"
                        style="width: auto" autocomplete="off" disabled>
                    <option value="all" selected>Show All</option>
                    <option value="has_pubmed">Has Publications</option>
                    <option value="no_pubmed">No Publications</option>
                </select>
<<<<<<< HEAD
                <br/><br/>
                RNA Central Annotation:
                <span id="rna_central_annotation"></span>
=======
>>>>>>> origin/master
            </th>
        </tr>
        <tr>
            <th width="25%" style="white-space: normal">
                <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left"
                      title="Publications below are filtered according to the selected MeSH term and the filter type."></span>
                <span id="term_lbl"></span> Publications
            </th>
            <th width="25%">
                <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left"
                      title="GO Annotations for target/microRNA."></span>
                GO Annotations
            </th>
        </tr>
        </thead>
        <tbody id="results_body">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <br/><br/>
</div>
</body>
</html>
