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
			/* CC-��������, CD-������, DT-�޶���(the, some, my), EX-���ڴ�(there), FW-������, IN-��ʻ��ߴ�������, JJ-���ݴ�, JJR-���ݴʱȽϼ�, JJS-���ݴ���߼�
			   LS-�б���, MD-��̬����, NN-���ʣ�����, NNS-���ʣ�����, NNP-ר�����ʣ�����, NNPS-ר�����ʣ�����, PDT-ǰ���޶���, POS-���и����, PRP-�˳ƴ���,
               PRP$-���и������(prolog �汾 PRP-S), RB-����, RBR-���ʵıȽϼ�, RBS-���ʵ���߼�, RP-СƷ�ʣ��붯�ʹ��ɶ��ﶯ�ʵĸ��ʻ��ʣ�, SYM-����, TO-to, 
               UH-��̾��, VB-����ԭ��, VBD-���ʹ�ȥʽ, VBG-�����ʻ����ڷִ�, VBN-���ʹ�ȥ�ִ�, VBP-���ʣ��ǵ����˳Ƶ�������ʽ, VBZ-���ʣ������˳Ƶ�������ʽ, WDT-wh-�޶���,
               WP-wh-������, WP$-���и��, wh-������, WRB-wh-���� */
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
