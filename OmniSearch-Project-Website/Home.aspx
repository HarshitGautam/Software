<%@ Page Title="OmniSearch Project - Home" Language="C#" MasterPageFile="~/MasterPage.master"
    CodeFile="Home.aspx.cs" AutoEventWireup="true" Inherits="Home" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
    <style type="text/css">
        .textright
        {
            text-align: right;
            font-size: small;
        }
    </style>
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="panelMain" runat="server">
        <asp:Panel ID="panelLeft" runat="server" CssClass="leftcontent">
            <h1 style="text-align: center">Welcome to the OmniSearch Project</h1>
            <p class="textright">
                Funded by National Institutes of Health (NIH)/NCI <a href="http://itcr.nci.nih.gov/fp"
                    target="_blank">1U01CA180982-01A1</a>
                under <a href="http://itcr.nci.nih.gov/about-itcr" target="_blank">ITCR Initiative</a>
            </p>
            <br />
            <p style="text-align: justify">
                microRNAs (short for miRNAs or miRs) are a special class of small, non-coding RNA
                molecules and have been reported to perform important roles in various biological
                and pathological processes by regulating their respective target genes (short for
                targets). As such, miRNAs are closely associated with the development, diagnosis,
                and prognosis of various human diseases including cancer. In fact, prior research
                has demonstrated that miRNAs may provide critical insights with regard to many aspects
                of human diseases, including early diagnosis, personalized treatment, prognosis
                prediction, and so forth.
            </p>
            <br />
            <p style="text-align: justify">
                However, miRNA knowledge acquisition still remains challenging despite of many research
                efforts in this area. To completely understand and fully delineate miRNA functions,
                besides performing direct biological experiments in "wet" labs, biologists and bioinformaticians
                can also query PubMed and TarBase for biologically validated miRNA targets, and
                various miRNA target prediction databases or websites for computationally predicted
                targets as well. There exist two significant barriers in this scenario: (1) The
                number of distinct miRNA target prediction databases is in the neighborhood of 30.
                Moreover, different databases utilize different prediction algorithms, and more
                importantly, these databases have, more often than not, quite heterogeneous semantics
                (that is, the meaning of data) among each other. (2) Each individual miRNA may target
                on up to hundreds or even more genes. For each and every target gene, either biologically
                validated or computationally putative, it is often necessary to extract and obtain
                additional information from other data sources, such as gene expression, protein
                functions, and affiliated signaling pathways, because such additional information
                is critical in helping biologists to better explore functions performed by miRNAs.
                Similarly, these involved data sources are inherently heterogeneous with each other.
                In short, biologists and bioinformaticians are facing significant barriers in fully
                delineating miRNA functions and the following effective bio-curation.
            </p>
            <br />
            <p style="text-align: justify">
                To tackle the above-mentioned challenge, we will develop OmniSearch, a semantic
                search tool, to assist biologists, cancer biologists in particular, in unraveling
                critical roles of miRNAs in human cancers in an automated and highly efficient manner.
                We will handle the significant challenge of data sharing, data integration, and
                effective search in miRNA (and microgenomics at large) research in oncology.
                
            </p>


        </asp:Panel>
        <asp:Panel ID="panelRight" runat="server" Width="250px" CssClass="right">
            <h1 style="text-align: center">Latest News &nbsp;&nbsp
            </h1>
            <br />
            <asp:Panel ID="panelNews" runat="server" Height="420px" ScrollBars="Vertical" Width="250px">
                <asp:Panel ID="Panel12" runat="server">
                    <asp:Label ID="Label12" runat="server" CssClass="date">11/21/2015 - Paper Accepted</asp:Label><br />
                  Our paper, <b> A domain ontology for the non-coding RNA field</b>, was accepted and published in<em> Proc. </em> 2015 IEEE International Conference on Bioinformatics and Biomedicine (BIBM-15), IEEE, Washington D.C., Nov. 2015.  
                    <br />
                    <br />
                    <asp:Panel ID="Panel13" runat="server">
                    <asp:Label ID="Label13" runat="server" CssClass="date">11/21/2015 - Paper Accepted</asp:Label><br />
                   Our paper, <b> A semantic approach for knowledge capture of microRNA-target gene interactions</b>, was accepted and published in<em> Proc. </em>BHI Workshop at 2015 IEEE International Conference on Bioinformatics and Biomedicine (BIBM-15), IEEE, Washington D.C., Nov. 2015.<br />
		            <br />
                    <br />
                    <br />
                </asp:Panel>
                </asp:Panel>
                <asp:Panel ID="Panel3" runat="server">
                    <asp:Label ID="Label3" runat="server" CssClass="date">May 2015 – Book Editing</asp:Label><br />
                    We are editing the book of <em>Bioinformatics in microRNA research: computational methods
                        in exploring microRNAs' functions</em>, which is part of Springer series of
                    Methods in Molecular Biology.
                    <br />
                    <br />
                    <br />
                </asp:Panel>

                <asp:Panel ID="Panel11" runat="server">
                    <asp:Label ID="Label11" runat="server" CssClass="date">07/19/2014 - Paper Accepted</asp:Label><br />
                    Our paper, <b>OmniSearch: A Dynamic microRNA Domain Ontology for Microgenomics Knowledge
                        Discovery, Unification, and Bio-Curation</b>, was accepted and published by
                    <a href="http://www.plosone.org/article/info%3Adoi%2F10.1371%2Fjournal.pone.0100855"
                        target="_blank">PLOS ONE</a>.<br />
                    <br />
                    <br />
                </asp:Panel>

                <asp:Panel ID="Panel20" runat="server">
                    <asp:Label ID="Labe2l6" runat="server" CssClass="date">11/17/2013 - Paper Submitted</asp:Label><br />
                    Our paper, <b>OmniSearch: A Dynamic microRNA Domain Ontology for Microgenomics Knowledge
                        Discovery, Unification, and Bio-Curation</b>, was submitted to and is currently
                    under review in <a href="http://www.plosone.org/" target="_blank">PLOS ONE</a>.
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel6" runat="server">
                    <asp:Label ID="Label6" runat="server" CssClass="date">10/15/2013 - Paper Accepted</asp:Label><br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>Semi-Automated microRNA Ontology Development based on Artificial Neural
                        Networks</b>, was accepted and published in <em>Proc. 2013 IEEE International Conference
                            on Bioinformatics and Biomedicine</em> (<a href="http://bibm2013.tongji.edu.cn/"
                                target="_blank">BIBM 2013</a>), Shanghai, China, December 2013.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel7" runat="server">
                    <asp:Label ID="Label7" runat="server" CssClass="date">10/12/2013 - Paper Accepted</asp:Label><br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>Semantics-Driven Frequent Data Pattern Mining on Electronic Health Records
                        for Effective Adverse Drug Event Monitoring</b>, was accepted and published
                    in <em>Proc. 2013 IEEE International Conference on Bioinformatics and Biomedicine</em>
                    (<a href="http://bibm2013.tongji.edu.cn/" target="_blank">BIBM 2013</a>), Shanghai,
                    China, December 2013.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel8" runat="server">
                    <asp:Label ID="Label8" runat="server" CssClass="date">08/15/2012 - Paper Accepted</asp:Label><br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>An Ontology-Based MicroRNA Knowledge Sharing and Acquisition Framework</b>,
                    was accepted and published in <em>Proc. BHI Workshop at 2012 IEEE International Conference
                        on Bioinformatics and Biomedicine</em> (<a href="http://soc.southalabama.edu/~huang/papers/BIBM-12.pdf"
                            target="_blank">BIBM 2012</a>), Philadelphia, PA, October 2012.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel9" runat="server">
                    <asp:Label ID="Label9" runat="server" CssClass="date">12/17/2011 - Paper Accepted</asp:Label><br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>Knowledge Acquisition, Semantic Text Mining, and Security Risks in Health
                        and Biomedical Informatics</b>, was accepted and published by <em>World Journal of Biological
                            Chemistry, 3(2): 27-33</em> (<a href="http://soc.southalabama.edu/~huang/papers/WJBC-Press.pdf"
                                target="_blank">PDF</a>), Baishideng, February 2012 (doi: 10.4331/wjbc.v3.i2.27).
                    <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel10" runat="server">
                    <asp:Label ID="Label10" runat="server" CssClass="date">06/18/2011 - Paper Accepted</asp:Label>
                    <br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>OmniSearch: A Domain-Specific Knowledge Base for MicroRNA Target Prediction</b>,
                    was accepted and published by <em>Pharmaceutical Research (impact factor: 4.74)</em>
                    (<a href="http://soc.southalabama.edu/~huang/papers/PharmRes-Press.pdf" target="_blank">PDF</a>),
                    Springer, August 2011 (doi:10.1007/s11095-011-0573-8).
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel5" runat="server">
                    <asp:Label ID="Label5" runat="server" CssClass="date">08/28/2010 - Paper Accepted</asp:Label>
                    <br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>OmniSearch: Domain Ontology and Knowledge Acquisition in MicroRNA Target
                        Prediction</b>,
                        was accepted and published in <em>Proc. Ninth International Conference on Ontologies,
                            DataBases, and Applications of Semantics</em> (<a href="http://www.onthemove-conferences.org/index.php/odbase10"
                                target="_blank">ODBASE 2010</a>), Crete, Greece, October 2010.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel1" runat="server" Width="225px">
                    <asp:Label ID="Label1" runat="server" CssClass="date">06/21/2010 - Paper Accepted</asp:Label>
                    <br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>Ontology for MiRNA Target Prediction in Human Cancer</b>, was accepted
                    and published in <em>Proc. First ACM International Conference on Bioinformatics and
                        Computational Biology</em> (<a href="http://www.cse.buffalo.edu/ACM-BCB2010/" target="_blank">ACM-BCB
                            2010</a>), Niagara Falls, NY, August 2010.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel2" runat="server" Width="225px">
                    <asp:Label ID="Label2" runat="server" CssClass="date">06/06/2010 - Paper Accepted</asp:Label>
                    <br />
                    <!--<p style="text-align: justify">-->
                    Our paper, <b>Ontology-Based Knowledge Discovery and Sharing in Bioinformatics and Medical
                        Informatics: A Brief Survey</b>, was accepted and published by <em>Proc. Seventh International
                            Conference on Fuzzy Systems and Knowledge Discovery</em> (<a href="http://icnc-fskd2010.ytu.edu.cn/"
                                target="_blank">FSKD 2010</a>), Yantai, China, August 2010.
                        <!--</p>-->
                    <br />
                    <br />
                    <br />
                </asp:Panel>
                <asp:Panel ID="Panel4" runat="server">
                    <asp:Label ID="Label4" runat="server" CssClass="date">06/01/2010 - Ontology Submitted to NCBO (the National Center for Biomedical Ontology)</asp:Label>
                    <br />
                    <!--<p style="text-align: justify">-->
                    The OmniSearch ontology was submitted to the <b>NCBO BioPortal</b> (<a href="http://bioportal.bioontology.org/ontologies/OmniSearch"
                        target="_blank">OmniSearch in NCBO</a>).
                        <br />
                    <br />
                    <br />
                </asp:Panel>
            </asp:Panel>
            <asp:Image ID="Image1" runat="server" Height="101px" ImageUrl="~/images/doctor.jpg"
                Width="218px" />

        </asp:Panel>
    </asp:Panel>
</asp:Content>
