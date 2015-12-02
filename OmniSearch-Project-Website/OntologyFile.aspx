<%@ Page Title="OmniSearch Project - Ontology Files" Language="C#" AutoEventWireup="true"
    MasterPageFile="~/MasterPage.master"
    CodeFile="OntologyFile.aspx.cs" Inherits="OntologyFile" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="Panel1" runat="server" CssClass="content">
        <p style="text-align: justify">
            The OMIT ontology in the OmniSearch project was developed in both the OWL and OBO formats.
        </p>
        <br />
        <p style="text-align: justify">
            The most up-to-date ontology files can be accessible <a href="https://github.com/OmniSearch/omit" target="_blank"><b>here</b></a>. <br/><br/>
			Besides, the OMIT ontology is also included in both <a href="http://obofoundry.org/ontology/omit.html"
                target="_blank"><b>the OBO Library</b></a> and <a href="http://bioportal.bioontology.org/ontologies/OMIT"
                    target="_blank"><b>the NCBO BioPortal</b></a>.
        </p>
        <br />
		<!--
        <div class="hr">
            <hr />
        </div>
        <br />
        <br />
        <div class="hr">
            <hr />
        </div>
        <p align="center">
            &nbsp;
        </p>
        <p align="center">
            <a href="download.aspx?f=OMIT.obo" target="_blank">
                <img src="Images/Images_OF/download.gif" alt="Photo" width="20" height="20" />&nbsp;&nbsp;
                Download OMIT in OBO Format</a>
        </p>
        <br />
        <div class="hr">
            <hr />
        </div>
        <p align="center">
            &nbsp;
        </p>
        <p align="center">
            <a href="download.aspx?f=OMIT.owl" target="_blank">
                <img src="Images/Images_OF/download.gif" alt="Photo" width="20" height="20" />&nbsp;&nbsp;
                Download OMIT in OWL Format</a>
        </p>

        <br />
        <div class="hr">
            <hr />
        </div>
		-->
			
		<div class="hr">
            <hr />
        </div>		
        <br />
        <p style="text-align: justify">
            In this project, a reference ontology, NCRO, is also under development. NCRO stands
            for “Non-coding RNA Ontology” (pronounced as “an-ke-row”).
        </p>
        <br />
        <p style="text-align: justify">
            The NCRO ontology aims to provide a common set of terms and relations
            that will facilitate the curation, analysis, exchange, sharing, and management of
            ncRNA data. If research groups around the world utilize the NCRO to perform annotations
            on their ncRNA data, it will significantly enhance comparative analysis of the wealth
            of information from disparate sources.
        </p>
        <br />
        <p style="text-align: justify">All files for the NCRO ontology can be found <a href="https://github.com/OmniSearch/ncro" target="_blank"><b>here</b></a>.</p>
        <br />
		<div class="hr">
            <hr />
        </div>
		<br/>
		<div style="text-align:left">
			<b>References for OMIT:</b><br/><br/>
			1. J. Huang, J. Dang, G.M. Borchert, K. Eilbeck, H. Zhang, M. Xiong, W. Jiang, H. Wu, J.A. Blake, D.A. Natale, and M. Tan, <a href="http://www.plosone.org/article/info%3Adoi%2F10.1371%2Fjournal.pone.0100855" target="_blank">OMIT: Dynamic, Semi-Automated Ontology Development for the microRNA Domain</a>, PLOS ONE, 9(7): 1-16, e100855, July 2014 (doi:10.1371/journal.pone.0100855).<br/><br/>
			2. J. Huang, F. Gutierrez, D. Dou, J.A. Blake, K. Eilbeck, D.A. Natale, B. Smith, Y. Lin, X. Wang, Z. Liu, M. Tan, and A. Ruttenberg, <a href="http://soc.southalabama.edu/~huang/papers/BIBM-15-2.pdf" target="_blank">A semantic approach for knowledge capture of microRNA-target gene interactions</a>, Proc. BHI Workshop at 2015 IEEE International Conference on Bioinformatics and Biomedicine (BIBM-15), IEEE, Washington D.C., November 2015.
		</div>
		<br/><br/>
		<div style="text-align:left">
			<b>References for NCRO:</b><br/><br/>
			1. J. Huang, K. Eilbeck, J.A. Blake, D. Dou, D.A. Natale, A. Ruttenberg, B. Smith, M.T. Zimmermann, G. Jiang, Y. Lin, B. Wu, Y. He, S. Zhang, X. Wang, H. Zhang, Z. Liu, and M. Tan, <a href="http://soc.southalabama.edu/~huang/papers/BIBM-15-1.pdf" target="_blank">A domain ontology for the non-coding RNA field</a>, Proc. 2015 IEEE International Conference on Bioinformatics and Biomedicine (BIBM-15), IEEE, Washington D.C., November 2015.
		</div>
		
    </asp:Panel>
</asp:Content>
