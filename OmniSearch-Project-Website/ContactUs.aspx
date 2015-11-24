<%@ Page Title="OmniSearch Project - Contact Us" Language="C#" AutoEventWireup="true" MasterPageFile="~/MasterPage.master"
    CodeFile="ContactUs.aspx.cs" Inherits="ContactUs" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="Panel1" runat="server" CssClass="left">
        <p>
            <b>Dr. Jingshan Huang</b>
        </p>
        <p>
            University of South Alabama
        </p>
        <p>
            School of Computing
        </p>
        <p>
            Shelby Hall, SHEC 1123
        </p>
        <p>
            150 Jaguar Drive
        </p>
        <p>
            Mobile, AL 36688
        </p>
        <p>
            U.S.A.
        </p>
        <p>
            <b>Phone: </b>+1-251-460-7612 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Fax: </b>+1-251-460-7274
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <script type="text/jscript" language="javascript" src="js/Email.js"></script>
            <b>Email: </b><a href="javascript:sendEmail('6875616E6740736F757468616C6162616D612E656475')"
                class="STYLE31"></a><span class="STYLE31"></span><a href="javascript:sendEmail('6875616E6740736F757468616C6162616D612E656475')">
                    his last name in southalabama domain</a>
            <%--		<a href="mailto:huang@usouthal.edu">his last name in southalabama domain</a> --%>
        </p>
    </asp:Panel>
</asp:Content>
