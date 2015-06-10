import java.io.File;
import java.io.IOException;
import java.io.StringReader;
import java.util.HashSet;
import java.util.Set;

import opennlp.tools.cmdline.PerformanceMonitor;
import opennlp.tools.cmdline.postag.POSModelLoader;
import opennlp.tools.postag.POSModel;
import opennlp.tools.postag.POSSample;
import opennlp.tools.postag.POSTaggerME;
import opennlp.tools.tokenize.WhitespaceTokenizer;
import opennlp.tools.util.ObjectStream;
import opennlp.tools.util.PlainTextByLineStream;

public class MessageAnalyzer {
	private String strMessage;       // strMessage represents message string
	private Set<String> MessageConcept; // MessageNoun is used for collecting name of concepts
	int judgeNoun = 1;
	/* set message. */
	public MessageAnalyzer(String strM)
	{
		strMessage = strM;
		MessageConcept = new HashSet<String>();
	}
	
	/* extract concepts from the message. */
	public void extractingOfMessage() throws IOException
	{
		POSModel model = new POSModelLoader().load(new File("en-pos-maxent.bin")); 
		PerformanceMonitor perfMon = new PerformanceMonitor(System.err, "sent");
		POSTaggerME tagger = new POSTaggerME(model);

		ObjectStream<String> lineStream = new PlainTextByLineStream(new StringReader(strMessage));
	 
		perfMon.start();
		String line, abstr ="", nouns = "", wordProperty = "";
		String[] strNouns;
		while ((line = lineStream.read()) != null) 
		{
			String whitespaceTokenizerLine[] = WhitespaceTokenizer.INSTANCE.tokenize(line);
			String[] tags = tagger.tag(whitespaceTokenizerLine);
	 
			POSSample sample = new POSSample(whitespaceTokenizerLine, tags);
			System.out.println(sample.toString());
			abstr = abstr + " " + sample.toString();
			perfMon.incrementCounter();
		}
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
						if(nouns.charAt(nouns.length()-1) == '.')
							nouns = nouns.substring(0, nouns.length()-1);
						if(nouns.contains("."))
						{
							strNouns = nouns.split("\\.");
							for(int j = 0; j < strNouns.length; j++)
							{
								strNouns[j] = strNouns[j].trim();
								strNouns[j] = strNouns[j].replaceAll("/", "");
								if(strNouns[j].length() < 2)
									continue;
								System.out.println(strNouns[j]);
								MessageConcept.add(strNouns[j]);
							}
						}
						else
						{
							nouns = nouns.trim();
							nouns = nouns.replaceAll("/", "");
							if(nouns.length() < 2)
								continue;
							System.out.println(nouns);
							MessageConcept.add(nouns);
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
						strNouns[j] = strNouns[j].trim();
						strNouns[j] = strNouns[j].replaceAll("/", "");
						if(strNouns[j].length() >= 2)
							MessageConcept.add(strNouns[j]);
						System.out.println(strNouns[j]);
					}
				}
				else
				{
					nouns = nouns.trim();
					nouns = nouns.replaceAll("/", "");
					if(nouns.length() >= 2)
						MessageConcept.add(nouns);
					System.out.println(nouns);
				}
			}
		}
		nouns = "";
		wordProperty = "";
		perfMon.stopAndPrintFinalResult();
	}
	
	/* clear message concepts. */
	public void clearMessageConcept()
	{
		MessageConcept.clear();
	}
	
	/* return message Concept set */
	Set<String> returnMessageConceptSet()
	{
		return MessageConcept;
	}
}
