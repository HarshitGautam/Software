<%@ Page Title="Software" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Software.aspx.cs" Inherits="SoftwareDownloads" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
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
	
	<!--
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
	-->
	
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="Panel1" runat="server" CssClass="content">
	<a href="http://omnisearch.soc.southalabama.edu:8080" target="_blank" style="text-align:center;font-size:17px"><b>Click here to enter the OmniSearch User Interface.</b></a>
	<!--
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
-->

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
	  <select id="select1" style="width: 50%; font-size: 18px" runat="server">
          <option></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
      </select></td>
                    <td style="width: 430px" align="right">Choose or type a phenotype&nbsp;&nbsp;
                            <select id="select2" runat="server" name="select2" style="width: 50%; font-size: 18px">
                                <option></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select></td>
                    <td align="right" style="width: 155px">
                            <asp:Button ID="search" runat="server" OnClick="search_Click" Style="font-size: 17px" Text="Search" />
                        &nbsp;&nbsp;
                            <asp:Button ID="reset" runat="server" OnClick="reset_Click" Style="font-size: 17px" Text="Reset" />
                    </td>
                </tr>
            </table>
            <br />
            <br />

            <table style="width: 95%; text-align: center; align-content: center; align-items: center; align-self: center" border="1" id="table" align="center" visible="false" runat="server">
                <tr style="height: 20px; font-size: 18px; padding: 0px">
                    <th style="width: 10%" rowspan="2"><strong>Candidate Targets</strong></th>
                    <th rowspan="2" style="width: 11%"><strong>Predicated By</strong></th>
                    <th style="width: 84%" colspan="2"><strong>Evidence</strong></th>
                </tr>
                <tr>
                    <td style="height: 20px; width: 42%">
                        <table style="width: 100%">
                            <tr>
                                <th align="left" style="font-size: 17px">&nbsp;&nbsp;<strong>&quot;Drug resistance&quot; Publications</strong></th>
                                <th align="right">
                                    <img src="Images/tip.png" alt="" title="tooltip" width="23" height="23" />&nbsp;&nbsp;</th>
                            </tr>
                        </table>
                    </td>
                    <td style="height: 20px; width: 42%">
                        <table style="width: 100%">
                            <tr>
                                <td align="left" style="font-size: 17px"><strong>&nbsp;&nbsp;Go Annotation</strong></td>
                                <td align="right">
                                    <img src="Images/tip.png" alt="" title="tooltip" width="23" height="23" />&nbsp;&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 18px"><strong>HSA</strong></th>
                    <td>
                        <br />
                        <a href="http://mirdb.org/miRDB/" target="_blank">miRDB</a><br />
                        <a href="http://www.targetscan.org/" target="_blank">TargetScan</a><br />
                        <a href="http://www.microrna.org/microrna/home.do/" target="_blank">microRNA.org</a><br />
                        <br />
                    </td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.google.com/" target="_blank">BAK-2&nbsp;&nbsp;(<a href="http://www.facebook.com/" target="_blank">2</a>)<br />
                            <a href="https://www.facebook.com/" target="_blank">Both&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">3</a>)<br /></td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA</a>&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.facebook.com/" target="_blank">mRNA</a>&nbsp;&nbsp;(<a href="https://www.facebook.com/" target="_blank">2</a>)<br />
                    </td>


                </tr>
                <tr>
                    <th style="font-size: 18px"><strong>BAK-2</strong></th>
                    <td>
                        <br />
                        <a href="http://mirdb.org/miRDB/" target="_blank">miRDB</a><br />
                        <a href="http://www.targetscan.org/" target="_blank">TargetScan</a><br />
                        <a href="http://www.microrna.org/microrna/home.do/" target="_blank">microRNA.org</a><br />
                        <br />
                    </td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.google.com/" target="_blank">BAK-2&nbsp;&nbsp;(<a href="http://www.facebook.com/" target="_blank">2</a>)<br />
                            <a href="https://www.facebook.com/" target="_blank">Both&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">3</a>)<br /></td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA</a>&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.facebook.com/" target="_blank">mRNA</a>&nbsp;&nbsp;(<a href="https://www.facebook.com/" target="_blank">2</a>)<br />
                    </td>


                </tr>
                <tr>
                    <th style="font-size: 18px"><strong>BAK-3</strong></th>
                    <td>
                        <br />
                        <a href="http://mirdb.org/miRDB/" target="_blank">miRDB</a><br />
                        <a href="http://www.targetscan.org/" target="_blank">TargetScan</a><br />
                        <a href="http://www.microrna.org/microrna/home.do/" target="_blank">microRNA.org</a><br />
                        <br />
                    </td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.google.com/" target="_blank">BAK-2&nbsp;&nbsp;(<a href="http://www.facebook.com/" target="_blank">2</a>)<br />
                            <a href="https://www.facebook.com/" target="_blank">Both&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">3</a>)<br /></td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA</a>&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.facebook.com/" target="_blank">mRNA</a>&nbsp;&nbsp;(<a href="https://www.facebook.com/" target="_blank">2</a>)<br />
                    </td>


                </tr>
                <tr>
                    <th style="font-size: 18px"><strong>BAK-4</strong></th>
                    <td>
                        <br />
                        <a href="http://mirdb.org/miRDB/" target="_blank">miRDB</a><br />
                        <a href="http://www.targetscan.org/" target="_blank">TargetScan</a><br />
                        <a href="http://www.microrna.org/microrna/home.do/" target="_blank">microRNA.org</a><br />
                        <br />
                    </td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.google.com/" target="_blank">BAK-2&nbsp;&nbsp;(<a href="http://www.facebook.com/" target="_blank">2</a>)<br />
                            <a href="https://www.facebook.com/" target="_blank">Both&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">3</a>)<br /></td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA</a>&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.facebook.com/" target="_blank">mRNA</a>&nbsp;&nbsp;(<a href="https://www.facebook.com/" target="_blank">2</a>)<br />
                    </td>


                </tr>
                <tr>
                    <th style="font-size: 18px"><strong>BAK-5</strong></th>
                    <td>
                        <br />
                        <a href="http://mirdb.org/miRDB/" target="_blank">miRDB</a><br />
                        <a href="http://www.targetscan.org/" target="_blank">TargetScan</a><br />
                        <a href="http://www.microrna.org/microrna/home.do/" target="_blank">microRNA.org</a><br />
                        <br />
                    </td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.google.com/" target="_blank">BAK-2&nbsp;&nbsp;(<a href="http://www.facebook.com/" target="_blank">2</a>)<br />
                            <a href="https://www.facebook.com/" target="_blank">Both&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">3</a>)<br /></td>
                    <td><a href="https://www.facebook.com/" target="_blank">HSA</a>&nbsp;&nbsp;(<a href="https://www.google.com/" target="_blank">1</a>)<br />
                        <a href="https://www.facebook.com/" target="_blank">mRNA</a>&nbsp;&nbsp;(<a href="https://www.facebook.com/" target="_blank">2</a>)<br />
                    </td>


                </tr>

            </table>
        </center>
        <br />
        <br />
        <center>
            <table>
                <tr>
                    <th style="width: 170px">
                        <input type="button" id="analysis" name="analysis" value="DAVID Pathway Analysis" style="font-size: 16px" onclick="window.open('http://www.google.com', '_blank')" />
                    <th style="width: 250px">
                        <asp:Button runat="server" ID="download" Text="Download Results" Style="font-size: 16px" OnClick="download_Click" /></th>
                    <th style="width: 23px">
                        <asp:Button runat="server" ID="clear" Text="Clear Results" Style="font-size: 16px" OnClick="clear_Click" /></th>
                </tr>
            </table>
        </center>
        <p></p>
        <p>&nbsp;</p>

        <p style="text-align: justify">
            All software tools developed in the OmniSearch project can be downloaded here.
        </p>
        <asp:HyperLink ID="HyperLink1" NavigateUrl="https://github.com/OmniSearch/Software" runat="server" Target="_blank">[Executable]</asp:HyperLink>&nbsp;&nbsp;
        <asp:HyperLink ID="HyperLink2" NavigateUrl="https://github.com/OmniSearch/Software" runat="server" Target="_blank">[Source]</asp:HyperLink>

-->
    </asp:Panel>
</asp:Content>


