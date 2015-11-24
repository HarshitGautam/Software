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
	
	
	
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="Panel1" runat="server" CssClass="content">
		<div style="text-align:center;width:100%">
			<p style="text-align: center">All software tools developed in the OmniSearch project can be downloaded here. <a href="https://github.com/OmniSearch/Software" target="_blank">[Executable]</a>&nbsp;&nbsp;<a href="https://github.com/OmniSearch/Software" target="_blank">[Source]</a></p>	
			<br/>
			<br/>
			<br/>
			<a href="http://omnisearch.soc.southalabama.edu:8080" target="_blank" style="font-size:27px"><b>Click here to enter the OmniSearch User Interface.</b></a>
		</div>
	
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


