using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

public partial class download : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string v_filename = Request.QueryString["f"].ToString();
        Response.Clear();
        Response.ClearHeaders();
        Response.ClearContent();
        Response.Buffer = false;
        Response.ContentType = "application/octet-stream";
        Response.AppendHeader("Content-Disposition", "attachment; filename=" + System.Web.HttpUtility.UrlEncode(v_filename, System.Text.Encoding.UTF8));
        Response.WriteFile(Server.MapPath("Ontology") + "\\" + v_filename);
        Response.Flush();
        Response.End();
    }
}