import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;
import java.util.Vector;


public class Comparer {
	private Vector<String> OntologyNameSet;   // OntologyNameSet collects name of concepts and relationships from ontology
	private Vector<String> DefinitionSet;     // DefinitionSet collects definition of concepts from ontology
	private Set<String> SourceNounsSet;  // SourceNounsSet collects name of concept from message
	private String strInexistence, strExistence, strSimilarship;    // strInexistence represents inexistence concept, strExistence represents existence concept, and strSimilatshiprepresents similar concept. These concepts are from message
	
	/* set OntologySet, MessageSet, DefinitionSet, strInexistence, and strExistence. */
	public Comparer(Vector<String> ONset, Set<String> Mset, Vector<String> Dset)
	{
		OntologyNameSet = ONset;
		SourceNounsSet = Mset;
		DefinitionSet = Dset;
		strInexistence = "";
		strExistence = "";
		strSimilarship = "";
	}
	
	/* return existence. */
	public String returnExistence()
	{
		return strExistence;
	}
	
	/* return inexistence. */
	public String returnInexistence()
	{
		return strInexistence;
	}
	
	/* return similatship. */
	public String returnSimilarship()
	{
		return strSimilarship;
	}
	/* clear all sets. */
	public void clearSet()
	{
		OntologyNameSet.clear();
		SourceNounsSet.clear();
		DefinitionSet.clear();
	}
	
	/* compare similar between two strings */
	float similarShip(String strS, String strT)
	{
		int indel, mismatch, match;
		int V[][] = new int[strS.length()+1][strT.length()+1];
		/* similarityMatrixValue is the value that comes from the similarity matrixValue */
		/* deleteValue is the value that comes from the delete */
		/* insertValue is the value that comes from the insert */
		int similarityMatrixValue, deleteValue, insertValue;
		/* maxValue is the max value between similarityMatrixValue, deleteValue and insertValue */
		int maxValue;
		/* Initial First Cell of LCS table */
		indel = -1;
		mismatch = -1;
		match = 5;
		V[0][0] = 0;
		/* Calculating cells of the first row of V table */
		for(int j = 1; j <= strT.length(); j++)        
		{
			V[0][j] = 0;
		}
		/* Calculating cells of the first colunm of V table */
		for(int i = 1; i <= strS.length(); i++)        
		{
			V[i][0] = 0;
		}
		/* Calculating other cells of V table */
		for(int i = 1; i <= strS.length(); i++)        
		{
			for(int j = 1; j <= strT.length(); j++)
			{
				if(strS.charAt(i-1) == strT.charAt(j-1))
					similarityMatrixValue = V[i-1][j-1] + match;
				else
					similarityMatrixValue = V[i-1][j-1] + mismatch;
				deleteValue = V[i-1][j] + indel;
				insertValue = V[i][j-1] + indel;
				/* Calculating the max value */
				maxValue = similarityMatrixValue;
				if(deleteValue > maxValue)
					maxValue = deleteValue;
				if(insertValue > maxValue)
					maxValue = insertValue;
				/* Let current cell is the max value */
				V[i][j] = maxValue;
			}
		}
		if(strT.length() >= strS.length())
			return (float)V[strS.length()][strT.length()]/(match*strT.length());
		else
			return (float)V[strS.length()][strT.length()]/(match*strS.length());
	}
	
	/* compare Ontology concepts and relationships names with Message concepts. */
	public void compareSourceAndOntology() throws IOException
	{
		FileWriter Fw = new FileWriter(new File("Comparer.doc"));
		BufferedWriter Bw = new BufferedWriter(Fw);
		float score, maxScore;     // score represents a temporary value for each score of similarship between concept and noun, maxScore represents max score between a concept and the noun
		String strSimilar = "", strConcept = "";
		Set<String> SimilarConcepts = new HashSet<String>();   // SimilarConcepts collects related concepts and relationships with nouns
		Vector<String> RelatedConcept = new Vector<String>();  
		Vector<String> RelatedNouns = new Vector<String>();
		Iterator<String> SourceNounsSetiterator = SourceNounsSet.iterator();
		Object MessageElement;
		/* compare each of nouns to each of concepts */
		while(SourceNounsSetiterator.hasNext())
		{
			MessageElement = SourceNounsSetiterator.next();
			maxScore = 0;
			for(int i = 0; i < OntologyNameSet.size(); i++) 
			{  
				if(OntologyNameSet.elementAt(i).equals(MessageElement.toString()))
				{
					strExistence = strExistence + MessageElement.toString() + "\n";
					break;
				}
				else
				{
					score = similarShip(MessageElement.toString(), OntologyNameSet.elementAt(i).toString());
					if(score > maxScore)
					{
						maxScore = score;
						strConcept = OntologyNameSet.elementAt(i).toString();
					}
				}
			}
			if(maxScore < 0.39)
			{
				strInexistence = strInexistence + MessageElement.toString() + "\n";
				continue;
			}
			else
			{
				RelatedConcept.add(strConcept);
				RelatedNouns.add(MessageElement.toString());
				SimilarConcepts.add(strConcept);
			}
		} 
		/* obtain concept of ontology and nouns of sources */
		for(String str : SimilarConcepts)
		{
			//System.out.println(SimilarConcepts.);
			for (int j = 0; j < RelatedConcept.size(); j++) 
			{
			    if(str.equals(RelatedConcept.elementAt(j).toString()))
			    {
			    	strSimilar = strSimilar + RelatedNouns.elementAt(j).toString() + "\n";
			    }
			}
			if(!strSimilar.isEmpty())
			{
				strSimilarship = strSimilarship + str.toString() + " (OMIT):" + "\n" + strSimilar + "\n";
				strSimilar = "";
			}
		}
		Bw.write("<-------------------------------------------Existence------------------------------------------->\n" + strExistence + "\n"
				+ "<-------------------------------------------Inexistence------------------------------------------->\n" + strInexistence + "\n"
				+ "<-------------------------------------------Similar------------------------------------------->\n" + strSimilarship);
		Bw.close();
		Fw.close();
		SimilarConcepts.clear();
		RelatedConcept.clear();
		RelatedNouns.clear();
		clearSet();
	}
}
