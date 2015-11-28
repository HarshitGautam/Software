<%@ Page Title="OmniSearch Project - Ontology Files" Language="C#" AutoEventWireup="true"
    MasterPageFile="~/MasterPage.master"
    CodeFile="OntologyFile.aspx.cs" Inherits="OntologyFile" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="Panel1" runat="server" CssClass="content">
        <p style="text-align: justify">
            The OMIT ontology in the OmniSearch project was developed in both OBO and OWL formats.
        </p>
        <br />
        <p style="text-align: justify">
            Most up-to-date ontology files can be downloaded using links below. They are also
            available in both OBO Foundry (<a href="http://www.obofoundry.org/cgi-bin/detail.cgi?id=omit"
                target="_blank"><b>OMIT in OBO Foundry</b></a>) and NCBO BioPortal (<a href="https://bioportal.bioontology.org/ontologies/OMIT"
                    target="_blank"><b>OMIT in NCBO</b></a>) websites.
        </p>
        <br />
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
        <p style="text-align: justify">All files for the NCRO ontology can be found on the GitHub site: <a href="https://github.com/OmniSearch/ncro" target="_blank">The NCRO ontology files on the GitHub Website</a>.</p>
      <br />
    </asp:Panel>
</asp:Content>
