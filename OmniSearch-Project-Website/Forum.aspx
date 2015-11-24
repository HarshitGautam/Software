<%@ Page Title="OmniSearch Project - Forum" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Forum.aspx.cs" Inherits="Forum" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <table style="font-family: Arial, Helvetica, sans-serif; font-size: x-large; font-style: normal; color: #000000;" 
            width="100%">
            <tr style="width:40%;height:10px;" ><td><br /></td></tr>
    <tr >
    <td style="width:40%;height:7px; text-align:right">
        This forum is based on the&nbsp;<asp:HyperLink ID="hl2" runat="server" NavigateUrl="https://wiki.nci.nih.gov/display/LexWiki/LexWiki" ToolTip=" Click to link LexWiki" Target="_blank"><b>LexWiki</b></asp:HyperLink>&nbsp;platform.</td>
    <td style="width:50%; height:7px; text-align:left">&nbsp;<asp:HyperLink ID="hl1" runat="server" ImageUrl="~/Images/link.png" NavigateUrl="https://wiki.nci.nih.gov/display/LexWiki/LexWiki" ToolTip=" Click to link LexWiki" Target="_blank"></asp:HyperLink>
    </td>
    </tr>
        </table>

</asp:Content>

