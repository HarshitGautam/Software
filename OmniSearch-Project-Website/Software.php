

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
	Software
</title><link href="/css/StyleSheet.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        a
        {
            text-decoration: none;
        }

        .paddingr
        {
            padding-right: 18px;
        }
    </style>
    
    <title>OmniSearch</title>
    <style type="text/css">
        <!--

        .title {
            font-size: 33px;
            width: 100%;
        }

        .search {
            font-size: 36px;
        }
        -->
    </style>
	
	
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/css/main.css" />
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="/js/main.js"></script>
	
	
</head>
<body>
    <form method="post" action="Software.aspx" id="form1" class="wrapper">
<div class="aspNetHidden">
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMTA3MDg5MDAxMmRklIAImAua2gkbKAJuw9pBwDyRcOaxPjGDSLZyPzAzaj0=" />
</div>

<div class="aspNetHidden">

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="F925E604" />
	<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEdAAfCr9+QYq5E+KzJvoP2KSRf27DnLnYbONixhkqIerlNsUZbuzlhus8UjuUj0JNejiZ15Zxeue+TBDc8y/PSFUQgl/Y3RVBta+Irp9pFulPfvOEog0jkL7kZoWimpJT+u/FulHtEYvSfNgtq37ri3V6GZ5hG+MnRkGb5Q8SiFS1erw7YD24fa7beufDl9V+HpcM=" />
</div>
        <div>
            <div id="panelTop">
	
                <table width="100%">
                    <tr>

                        <td style="vertical-align: top; width: 50px">
                            <img id="imgPlus" src="Images/plus.gif" style="height:50px;width:50px;" /></td>
                        <td>
                            <img id="imgomniProject" src="Images/omni.png" /></td>
                        <td style="vertical-align: top; text-align: right"><b>
                            <a href="http://biomedical.cis.usouthal.edu" style="padding-right: 10px;"
                                target="_blank">SBI Research Group</a></b>
                            <br />
                            <br />
                            <img id="nci" src="Images/nci.png" style="height:57px;width:58px;" />
                            &nbsp;
                            <img id="nih" class="paddingr" src="Images/nih.png" style="height:57px;width:58px;" />
                        </td>
                    </tr>
                </table>

            
</div>
            <div id="panelTopImage">
	
                <img id="imgTopImage" src="Images/topimage.jpg" style="height:150px;width:1200px;" />
            
</div>
            <div id="panelMenu" class="menu">
	
                <ul style="width: 100%; height: 30px; font-family: Arial, Helvetica, sans-serif;
                    color: #000000;">
                    <li><a href="Home.aspx" style="width: 7%">Home</a></li>
                    <li><a href="ProjectMembers.aspx" style="width: 15%">Project Members</a></li>
                    <li><a href="CollaboratingLabs.aspx" style="width: 17%">Collaborating Labs</a></li>
                    <li><a href="OntologyFile.aspx" style="width: 13%">Ontology Files</a></li>
                    <li><a href="Software.aspx" style="width: 11%">Software</a></li>
                    <li><a href="Publications.aspx" style="width: 11%; height: 30px;">Publications</a>
                    </li>
                    <li><a href="Forum.aspx" style="width: 17%">Discussion Forum</a></li>
                    <li><a href="ContactUs.aspx">Contact Us</a></li>
                </ul>
                <div class="hr">
                    <hr />
                </div>
            
</div>
            
    <div id="ContentPlaceHolder1_Panel1" class="content">
	
	
		
		<div style="display: table; margin: 0 auto">
		<table style="width: 100%">
            <tr>
                <td>
                    <img src="/images/logo.png" class="img-responsive" style="margin: 0; height: 150px"/>
                </td>
                <td>
                    <h1 id="header" style="text-align: right">OmniSearch User Interface</h1>
                </td>
            </tr>
        </table>
		<br/>
        <p style="text-align: center">All software tools developed in the OmniSearch project can be downloaded here. <a href="https://github.com/OmniSearch/Software" target="_blank">[Executable]</a>&nbsp;&nbsp;<a href="https://github.com/OmniSearch/Software" target="_blank">[Source]</a></p>
        <br/>
		<form id="search_form">
			<table id="search_controls" class="controls">
                <thead>
                    <tr>
                        <th style="padding-left: 0">
                            Enter a microRNA
                            <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left" title="Begin typing a complete microRNA name or part of such a name. Or simply press the down-arrow key without typing anything."></span>
                        </th>
                        <th>
                            Enter a MeSH Term
                            <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left" title="Begin typing a complete MeSH Term or part of such a term. Or simply press the down-arrow key without typing anything."></span>
                        </th>
                        <th>
                            MeSH Term Filter
                            <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left" title="Selecting Broader/Narrower MeSH Term will result in filtering on BOTH the selected MeSH term PLUS its broader/narrower terms."></span>
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
                                <input id="mirna_searchbox_input" type="text" class="form-control" autocomplete="off" required />
                                <div id="mirna_searchbox_div"></div>
                            </div>
                        </td>
                        <td>
                            <div id="term_searchbox" class="searchbox">
                                <input id="term_searchbox_input" type="text" class="form-control" autocomplete="off" required />
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
                            <button #id="search_btn" type="submit" class="btn btn-default btn-block" autocomplete="off">Search</button>
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
                        <option selected>5</option>
                        <option>10</option>
                        <option>30</option>
                        <option>50</option>
                        <option>100</option>
                        <option>All</option>
                    </select>
				</td>
				<td style="padding-left: 8px">
					<button id="first_btn" type="button" class="btn btn-default" style="margin-bottom: 0; border-right: 0 none; border-top-right-radius: 0; border-bottom-right-radius: 0" autocomplete="off" disabled>&laquo;</button>
                </td>
                <td>
                    <button id="prev_btn" type="button" class="btn btn-default" style="margin-bottom: 0; border-radius: 0" autocomplete="off" disabled>&lsaquo;</button>
                </td>
                <td>
                    <label class="form-control" style="margin-bottom: 0; border-left: 0 none; border-right: 0 none; border-radius: 0; white-space: nowrap">Page <span id="page_lbl">0</span> of <span id="page_count_lbl">0</span></label>
                </td>
                <td>
                    <button id="next_btn" type="button" class="btn btn-default" style="margin-bottom: 0; border-radius: 0" autocomplete="off" disabled>&rsaquo;</button>
                </td>
                <td>
                    <button id="last_btn" type="button" class="btn btn-default" class="btn btn-default" style="margin-bottom: 0; border-left: 0 none; border-top-left-radius: 0; border-bottom-left-radius: 0" autocomplete="off" disabled>&raquo;</button>
				</td>
				<td style="padding-left: 8px">
                    <div id="download_results" class="download">
                        <button id="download_results_btn" type="button" class="btn btn-default" autocomplete="off" disabled>Download Results</button>
                        <div id="download_results_div">
                            <label><input id="download_all_radio" type="radio" name="amount"/> Download the whole table (<span id="total_count_lbl"></span>)</label><br/>
                            <label>Format: </label>
                            <select id="format" class="form-control">
                                <option value="tsv" selected>Tab-delimited text</option>
                                <option value="csv">CSV format</option>
                            </select><br/><br/>
                            <label><input id="download_selected_radio" type="radio" name="amount"/> Download selected targets (<span id="selected_count_lbl"></span>)</label><br/><br/>
                            <button id="download_btn" type="button" class="btn btn-default pull-right">Download</button>
                        </div>
                    </div>
				</td>
				<td style="padding-left: 8px">
                    <div id="perform_analysis" class="download">
                        <button id="perform_analysis_btn" type="button" class="btn btn-default" autocomplete="off" disabled>Perform Analysis</button>
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
                    <button id="clear_results_btn" class="btn btn-default" type="button" autocomplete="off" disabled>Clear Results</button>
                </td>
			</tr>
		</table>
		<table id="results">
			<thead>
				<tr>
					<th width="10%" rowspan="2"><label for="select_all_cb">Select All Targets<input id="select_all_cb" type="checkbox" autocomplete="off" disabled /></label></th>
					<th width="10%" rowspan="2">Candidate Targets</th>
					<th width="10%" rowspan="2">Predicted By</th>
					<th width="30%" colspan="2">Evidence</th>
				</tr>
				<tr>
					<th width="20%" style="white-space: normal">
                        <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left" title="Publications below are filtered according to the selected MeSH term and the filter type."></span>
                        <span id="term_lbl"></span> Publications
                    </th>
					<th width="20%">
                        <span class="glyphicon glyphicon-question-sign pull-right" data-toggle="tooltip" data-placement="left" title="GO Annotations for target/microRNA."></span>
                        GO Annotations
                    </th>
				</tr>
			</thead>
			<tbody id="results_body">
				<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			</tbody>
		</table>
	</div>
	<br/><br/>


<!--
        <center>
            <div class="title">
                <div>
                    <p>
                        OmniSearch User Interface
                    </p>
                </div>
            </div>
            <br />
            <br />
            <table>
                <tr>
                    <td style="width: 430px" align="left">Choose or type microRNA&nbsp;&nbsp;
	  <select name="ctl00$ContentPlaceHolder1$select1" id="ContentPlaceHolder1_select1" style="width: 50%; font-size: 18px">
		<option value=""></option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
	</select></td>
                    <td style="width: 430px" align="right">Choose or type a phenotype&nbsp;&nbsp;
                            <select name="ctl00$ContentPlaceHolder1$select2" id="ContentPlaceHolder1_select2" style="width: 50%; font-size: 18px">
		<option value=""></option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
	</select></td>
                    <td align="right" style="width: 155px">
                            <input type="submit" name="ctl00$ContentPlaceHolder1$search" value="Search" id="ContentPlaceHolder1_search" style="font-size: 17px" />
                        &nbsp;&nbsp;
                            <input type="submit" name="ctl00$ContentPlaceHolder1$reset" value="Reset" id="ContentPlaceHolder1_reset" style="font-size: 17px" />
                    </td>
                </tr>
            </table>
            <br />
            <br />

            
        </center>
        <br />
        <br />
        <center>
            <table>
                <tr>
                    <th style="width: 170px">
                        <input type="button" id="analysis" name="analysis" value="DAVID Pathway Analysis" style="font-size: 16px" onclick="window.open('http://www.google.com', '_blank')" />
                    <th style="width: 250px">
                        <input type="submit" name="ctl00$ContentPlaceHolder1$download" value="Download Results" id="ContentPlaceHolder1_download" style="font-size: 16px" /></th>
                    <th style="width: 23px">
                        <input type="submit" name="ctl00$ContentPlaceHolder1$clear" value="Clear Results" id="ContentPlaceHolder1_clear" style="font-size: 16px" /></th>
                </tr>
            </table>
        </center>
        <p></p>
        <p>&nbsp;</p>

        <p style="text-align: justify">
            All software tools developed in the OmniSearch project can be downloaded here.
        </p>
        <a id="ContentPlaceHolder1_HyperLink1" href="https://github.com/OmniSearch/Software" target="_blank">[Executable]</a>&nbsp;&nbsp;
        <a id="ContentPlaceHolder1_HyperLink2" href="https://github.com/OmniSearch/Software" target="_blank">[Source]</a>

-->
    
</div>

            <br />
            <div class="hr">
                <hr />
            </div>
            <div id="Panel1" class="footer">
	
                <center>
                    <a href="http://www.usouthal.edu" target="_blank">2012-2017 University of South Alabama</a></center>
                <br />
            
</div>
        </div>
    </form>
</body>
</html>
