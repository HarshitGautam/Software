import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;
import java.util.Vector;

public class OntologyAnalyzer {
	private InputStream F_in;
	private BufferedReader Br;
	private FileWriter Fw;
	private BufferedWriter Bw;
	private String pathOfOntology;
	private Vector<String> Ontology;   // Ontology is used for collecting name of concepts and relationships
	private Vector<String> Definition; // Definition is used for collecting definitions
	
	/* set the path of ontology file. */
	public OntologyAnalyzer(String strPath)
	{
		pathOfOntology = strPath;
		Ontology = new Vector<String>();
		Definition = new Vector<String>();
	}
	
	/* open ontology. */
	public void openOntology() throws IOException
	{
		F_in = new FileInputStream(pathOfOntology);
		Fw = new FileWriter(new File("OntologyConceptsRelationshipsName.doc"));
		Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
		Bw = new BufferedWriter(Fw);
	}
	
	/* close ontology. */
	public void closeOntology() throws IOException 
	{
		Br.close();
		Bw.close();
		Fw.close();
		Br = null;
		F_in = null;
	}
	
	/* extract concepts and relationships names from ontology */
	public void extractingOfOntology() throws IOException
	{
		String line;
		int definitionJudge;
		String conceptAndRelationName = "name:";                        // the keyword enables to identify names of concepts and relationships.
		String conceptName = "<owl:Class rdf:about=\"";                 // the keyword enables to identify names of concepts.
		String objectProName = "<owl:ObjectProperty rdf:about=\"#";     // the keyword enables to identify names of object property.
		String dataTypeProName = "<owl:DatatypeProperty rdf:about=\"#"; // the keyword enables to identify names of data type property.
		String OboDefinition = "def: \"";                               // the keyword enables to identify definition in OBO file.
		String OwlDefinition = ">Definition: ";                         // the keyword enables to identify definition in OWL file.
		openOntology();
		definitionJudge = 0;
		while((line = Br.readLine()) != null)
		{
			/* if line includes concept or relationship name in OBO file */
			if(line.indexOf(conceptAndRelationName, 0) >= 0)
			{
				line = line.replaceAll("name: ", "");
				Bw.write(line + "\n");
				Ontology.add(line);
				definitionJudge = definitionJudge + 1;
			}
			/* if line includes concept name in OWL file */
			else if(line.indexOf(conceptName, 0) >= 0)
			{
				line = line.replaceAll(conceptName, "");
				line = line.substring(0, line.length()-2);
				if(line.contains("#"))
					line = line.substring(5, line.length());
				for(int i = 0; i < line.length(); i++)
				{
					if(line.charAt(i) == ';')
					{
						line = line.substring(i+1, line.length());
						break;
					}
				}
				Bw.write(line + "\n");
				Ontology.add(line);
				definitionJudge = definitionJudge + 1;
			}
			/* if line includes object Property name in OWL file */
			else if(line.indexOf(objectProName, 0) >= 0)
			{
				line = line.replaceAll(objectProName, "");
				line = line.substring(4, line.length()-2);
				Bw.write(line + "\n");
				Ontology.add(line);
				definitionJudge = definitionJudge + 1;
			}
			/* if line includes data Property name in OWL file */
			else if(line.indexOf(dataTypeProName, 0) >= 0)
			{
				line = line.replaceAll(dataTypeProName, "");
				line = line.substring(4, line.length()-2);
				Bw.write(line + "\n");
				Ontology.add(line);
				definitionJudge = definitionJudge + 1;
			}
			/* if line includes definition in OBO file */
			else if(line.indexOf(OboDefinition, 0) >= 0)
			{
				line = line.replaceAll(OboDefinition, "");
				for(int i = 0; i < line.length(); i++)
				{
					if(line.charAt(i) == '"')
					{
						line = line.substring(0, i);
						break;
					}
				}
				Bw.write(line + "\n");
				Definition.add(line);
				definitionJudge = definitionJudge - 1;
			}
			/* if line includes definition in OWL file */
			else if(line.indexOf(OwlDefinition, 0) >= 0)
			{
				line = line.replaceAll(OwlDefinition, "");
				line = line.replaceAll("</rdfs:comment>", "");
				Bw.write(line + "\n");
				Definition.add(line);
				definitionJudge = definitionJudge - 1;
			}
			/* concept has not definition */
			if(definitionJudge == 2)
			{
				String str = "";
				definitionJudge = 1;
				Definition.add(str);
				Bw.write("zhanghe" + "\n");
			}
		}
		/* last concept or relationship has not definition */
		if(definitionJudge == 1)
		{
			String str = "";
			Definition.add(str);
		}
		closeOntology();
	}
	
	/* clear ontology and detinition. */
	public void clearOntology()
	{
		Ontology.clear();
		Definition.clear();
	}
	
	/* return ontology set */
	Vector<String> returnOntologySet()
	{
		return Ontology;
	}
	
	/* return definition set */
	Vector<String> returnDefinitionSet()
	{
		return Definition;
	}
}
