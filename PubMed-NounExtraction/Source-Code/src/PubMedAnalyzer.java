import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.StringReader;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.Vector;

import opennlp.tools.cmdline.PerformanceMonitor;
import opennlp.tools.cmdline.postag.POSModelLoader;
import opennlp.tools.postag.POSModel;
import opennlp.tools.postag.POSSample;
import opennlp.tools.postag.POSTaggerME;
import opennlp.tools.tokenize.WhitespaceTokenizer;
import opennlp.tools.util.ObjectStream;
import opennlp.tools.util.PlainTextByLineStream;

public class PubMedAnalyzer {
	private InputStream F_in;
	private BufferedReader Br;
	private FileWriter Fw;
	private BufferedWriter Bw;
	private String pathOfPubMedFile; // pathOfDataSourcesFile represents path of data sources file
	private Set<String> Concepts;   // Concepts are used for collecting all concepts in data sources
	private Vector<String> Nouns;   // Nouns are used for collecting all Nouns in data sources
	Map<String, Integer> mapConcept = new HashMap<String, Integer>(); // mapConcept is used for collecting concepts
	private String dataSources;     // dataSources is used for collecting all content of data sources
	private int limitationScore;    // limitationScore represents limitation score that sets accurate rate of concepts
	//private int limitationNumberOfPapers;  // limitationNumberOfPapers represents that analyze limit numbers of required papers
	private POSModel model; 
	private PerformanceMonitor perfMon;
	private POSTaggerME tagger;
	/* set the path of PubMed file. */
	public PubMedAnalyzer(String strPath)
	{
		pathOfPubMedFile = strPath;
		Concepts = new HashSet<String>();
		Nouns = new Vector<String>();
		limitationScore = 0;
		//limitationNumberOfPapers = 20;   // set up range of papers
	}
	
	/* open PubMed file. */
	public void openPubMed() throws IOException
	{
		F_in = new FileInputStream(pathOfPubMedFile);
		Fw = new FileWriter(new File("PubMedNouns.xls"));
		Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
		Bw = new BufferedWriter(Fw);
	}
	
	/* close PubMed file. */
	public void closePubMed() throws IOException 
	{
		Br.close();
		Bw.close();
		Fw.close();
		Br = null;
		F_in = null;
	}
	
	/* read PubMed file. */
	public void readPubMed() throws IOException
	{
		int judgeAuthorInformation, number = 0;
		String line, strLineCopyright;
		String strauthorInformation = "Author information:", strPmcid = "PMCID: ", strPmid = "PMID: ", strCopyright = "Copyright ";
		dataSources = "";
		openPubMed();
		judgeAuthorInformation = 0;
		
		model = new POSModelLoader().load(new File("en-pos-maxent.bin")); 
		perfMon = new PerformanceMonitor(System.err, "sent");
		tagger = new POSTaggerME(model);
		
		/* read PubMed file, and extract all abstracts of papers */ 	
		while((line = Br.readLine()) != null)
		{
			if(!line.trim().isEmpty())
				dataSources = dataSources + line + " ";
			else
			{
				if(!dataSources.trim().isEmpty() && dataSources.trim().length() > 160)
				{
					if(dataSources.indexOf(strauthorInformation, 0)>=0)
					{
						dataSources = "";
						continue;
					}
					if(dataSources.indexOf(strPmcid, 0)>=0)
					{
						dataSources = "";
						continue;
					}
					if(dataSources.indexOf(strPmid, 0)>=0)
					{
						dataSources = "";
						continue;
					}
					if(dataSources.indexOf(strPmid, 0)>=0)
					{
						dataSources = "";
						continue;
					}
					if(dataSources.indexOf(strCopyright, 0)>=0)
					{
						dataSources = "";
						continue;
					}
					extractingOfAbstract(dataSources);
					dataSources = "";
				}
				else
				{
					dataSources = "";
					continue;
				}
			}
			
			/* extract abstract of each paper */
//			if(line.length() > 12)
//			{
//				strLineCopyright = line.subSequence(0, 10).toString();
//				if(strLineCopyright.equals(strCopyright) && judgeAuthorInformation == 2)
//				{
//					judgeAuthorInformation = 0;
					
					/* avoid string only has space */
//					if(!dataSources.trim().isEmpty())
//					{
//						extractingOfAbstract(dataSources);
//					}
//					dataSources = "";
					/* calculate the number of papers */
//					number++;
//					System.out.println(number);
					/* limit to read the number of papers */
					//if(number > limitationNumberOfPapers)
						//break;
//				}
//			}
//			if((line.indexOf(strPmcid, 0)>=0 || line.indexOf(strPmid, 0)>=0) && judgeAuthorInformation == 2)
//			{
//				judgeAuthorInformation = 0;
				/* avoid string only has space */
//				if(!dataSources.trim().isEmpty())
//				{
//					extractingOfAbstract(dataSources);
//				}
//				dataSources = "";
				/* calculate the number of papers */
//				number++;
//				System.out.println(number);
				/* limit to read the number of papers */
				//if(number > limitationNumberOfPapers)
					//break;
//			}
//			if(line.indexOf(unrelatedInformation, 0) >= 0)
//			{
//				judgeAuthorInformation = 1;
//			}
//			if(line.length() == 0 && judgeAuthorInformation == 1)
//			{
//				judgeAuthorInformation = 2;
//			}
//			if(judgeAuthorInformation == 2)
//			{
//				dataSources = dataSources + line + " ";
//			}
		}
		/* write data in the document */
		writeData();
	}
	
	/* write concepts and times into PubMedNouns.doc */
	public void writeData() throws IOException
	{
		Iterator<String> ConceptsSetiterator = Concepts.iterator();
		Object ConceptsElement;
		int frequency;
		/* write unite concept name and concept frequency */
		Bw.write("Noun Phrase" + "\t" + "Frequency" + "\n");
		while(ConceptsSetiterator.hasNext())
		{
			ConceptsElement = ConceptsSetiterator.next();
			frequency = 0;
			for(int i = 0; i < Nouns.size(); i++)
			{
				if(Nouns.elementAt(i).contains(ConceptsElement.toString()))
				{
					frequency = frequency + 1;
				}
			}
			/* Enhance accurate rate of Concept */
			if(frequency > limitationScore && ConceptsElement.toString().length() > 2)
				Bw.write(ConceptsElement.toString() + "\t" + frequency + "\n");
		}
		closePubMed();
		System.out.println("Completed.");
		//sortConcept();
	}
	
	/* sort concept depend on frequency */
	public void sortConcept() throws IOException
	{
		F_in = new FileInputStream("PubMedNouns.xls");
		Fw = new FileWriter(new File("SortedPubMedNouns.xls"));
		Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
		Bw = new BufferedWriter(Fw);
		
		String line;
		int frequency;
		/* read PubMedNouns.doc */
		while((line = Br.readLine()) != null)
		{
			/* split concept name and concept frequency */
			String[] strline = line.split("\t"); 
			frequency = Integer.parseInt(strline[1]);
			/* put concept name and concept frequency in map */
			mapConcept.put(strline[0], frequency);
		}
		/* sorting conceptFrequencySet */
		List<Map.Entry<String, Integer>> conceptFrequencySet = new ArrayList<Map.Entry<String, Integer>>(mapConcept.entrySet());
		Collections.sort(conceptFrequencySet, new Comparator<Map.Entry<String, Integer>>() {   
		    public int compare(Map.Entry<String, Integer> o1, Map.Entry<String, Integer> o2) {      
		        return (o2.getValue() - o1.getValue()); 
		    }
		});
		/* after sort */
		for (int i = 0; i < conceptFrequencySet.size(); i++) 
		{
		    String str = conceptFrequencySet.get(i).toString();
		    str = str.replaceAll("=", "\t");
		    /* write SortedPubMedNouns.doc */
		    Bw.write(str + "\n");
		}
		closePubMed();
	}
	
	/* extract concepts from each abstract of multiple papers. */
	public void extractingOfAbstract(String strDataSource) throws IOException
	{
		//POSModel model = new POSModelLoader().load(new File("en-pos-maxent.bin")); 
		//PerformanceMonitor perfMon = new PerformanceMonitor(System.err, "sent");
		//POSTaggerME tagger = new POSTaggerME(model);
		ObjectStream<String> lineStream = new PlainTextByLineStream(new StringReader(strDataSource));
		//perfMon.start();
		String line, abstr ="", nouns = "", wordProperty = "";
		String[] strNouns;
		int NNPNumber;
		while ((line = lineStream.read()) != null) 
		{
			String whitespaceTokenizerLine[] = WhitespaceTokenizer.INSTANCE.tokenize(line);
			String[] tags = tagger.tag(whitespaceTokenizerLine);
	 
			POSSample sample = new POSSample(whitespaceTokenizerLine, tags);
			//System.out.println(sample.toString());
			abstr = abstr + " " + sample.toString();
			//perfMon.incrementCounter();
		}
		String[] strNNP = abstr.split(" ");
		NNPNumber = 0;
		for(int k = 0; k < strNNP.length; k++)
		{
			if(strNNP[k].indexOf(("_NNP"), 0) >= 0)
			{
				NNPNumber = NNPNumber + 1;
			}
		}
		if(NNPNumber > strNNP.length/2)
		{
			abstr = "";
		}
		if(!abstr.trim().isEmpty()){
		//System.out.println(abstr.trim());
		abstr = abstr.replaceAll("\\( ", "\\.");
		abstr = abstr.replaceAll("\\(", "\\.");
		abstr = abstr.replaceAll(" \\),", "\\.");
		abstr = abstr.replaceAll("\\),", "\\.");
		abstr = abstr.replaceAll(" \\)", "\\.");
		abstr = abstr.replaceAll("\\)", "\\.");
		abstr = abstr.substring(1, abstr.length());
		if(abstr.contains(","))
			abstr = abstr.replaceAll(",", "\\.");
		if(abstr.indexOf(("!"), 0) >= 0)
			abstr = abstr.replaceAll("\\!", "\\.");
		if(abstr.indexOf(("?"), 0) >= 0)
			abstr = abstr.replaceAll("\\?", "\\.");
		if(abstr.contains(":"))
			abstr = abstr.replaceAll(":", "\\.");
		if(abstr.contains(";"))
			abstr = abstr.replaceAll(";", "\\.");
		String[] strline = abstr.split(" ");
		for(int i = 0; i < strline.length; i++)
		{
			/* sparate word and word property into strl array */
			String[] strl = strline[i].split("_");
			/* CC-并列连词, CD-基数词, DT-限定词(the, some, my), EX-存在词(there), FW-外来词, IN-介词或者从属连词, JJ-形容词, JJR-形容词比较级, JJS-形容词最高级
			   LS-列表标记, MD-情态动词, NN-名词，单数, NNS-名词，复数, NNP-专有名词，单数, NNPS-专有名词，复数, PDT-前置限定词, POS-所有格结束, PRP-人称代词,
               PRP$-所有格代名词(prolog 版本 PRP-S), RB-副词, RBR-副词的比较级, RBS-副词的最高级, RP-小品词（与动词构成短语动词的副词或介词）, SYM-符号, TO-to, 
               UH-感叹词, VB-动词原形, VBD-动词过去式, VBG-动名词或现在分词, VBN-动词过去分词, VBP-动词，非第三人称单数现在式, VBZ-动词，第三人称单数现在式, WDT-wh-限定词,
               WP-wh-代名词, WP$-所有格的, wh-代名词, WRB-wh-副词 */
			if(strl[0].isEmpty())
				break;
			if(strl[1].equals("NN") || strl[1].equals("NNS") || strl[1].equals("JJ") || strl[1].equals("NNP") || strl[1].equals("NNPS") || strl[1].equals("VBG"))
			{
				strl[0] = strl[0].trim();
				nouns = nouns + " " + strl[0];
				wordProperty = strl[1];
			}
			else
			{
				if(!nouns.isEmpty() && !wordProperty.isEmpty())
				{
					if(wordProperty.equals("NN") || wordProperty.equals("NNS") || wordProperty.equals("NNP") || wordProperty.equals("NNPS"))
					{
						nouns = nouns.trim();
						if(nouns.contains("."))
						{
							strNouns = nouns.split("\\.");
							for(int j = 0; j < strNouns.length; j++)
							{
								if(!strNouns[j].isEmpty())
								{
									strNouns[j] = strNouns[j].trim();
									strNouns[j] = strNouns[j].replaceAll("/", "");
									if(strNouns[j].length() < 2)
										continue;
									//System.out.println(strNouns[j].trim());
									Concepts.add(strNouns[j]);
									Nouns.add(strNouns[j]);
								}
							}
						}
						else
						{
							if(!nouns.isEmpty())
							{
								nouns = nouns.trim();
								nouns = nouns.replaceAll("/", "");
								if(nouns.length() < 2)
									continue;
								//System.out.println(nouns.trim());
								Concepts.add(nouns);
								Nouns.add(nouns);
							}
						}
					}
				}
				nouns = "";
				wordProperty = "";
			}
		}
		if(!nouns.isEmpty() && !wordProperty.isEmpty())
		{
			if(wordProperty.equals("NN") || wordProperty.equals("NNS") || wordProperty.equals("NNP") || wordProperty.equals("NNPS"))
			{
				if(nouns.charAt(nouns.length()-1) == '.')
					nouns = nouns.substring(0, nouns.length()-1);
				if(nouns.contains("."))
				{
					strNouns = nouns.split("\\.");
					for(int j = 0; j < strNouns.length; j++)
					{
						if(!strNouns[j].isEmpty())
						{
							strNouns[j] = strNouns[j].trim();
							strNouns[j] = strNouns[j].replaceAll("/", "");
							if(strNouns[j].length() >= 2)
							{
								Concepts.add(strNouns[j]);
								Nouns.add(strNouns[j]);
								//System.out.println(strNouns[j].trim());
							}
						}
					}
				}
				else
				{
					if(!nouns.isEmpty())
					{
						nouns = nouns.trim();
						nouns = nouns.replaceAll("/", "");
						if(nouns.length() >= 2)
						{
							Concepts.add(nouns);
							Nouns.add(nouns);
							//System.out.println(nouns.trim());
						}
					}
				}
			}
		}
		nouns = "";
		wordProperty = "";
		lineStream.close();
		//perfMon.stopAndPrintFinalResult();
		}
	}
	
	/* clear Concepts and Nouns sets. */
	public void clearPubMed()
	{
		Concepts.clear();
		Nouns.clear();
		mapConcept.clear();
	}
	
	/* return Concepts set */
	Map<String, Integer> returnMapConcept()
	{
		return mapConcept;
	}
	
	/* return Concepts of PubMed */
	Set<String> returnPubMedConceptSet()
	{
		return Concepts;
	}
}
