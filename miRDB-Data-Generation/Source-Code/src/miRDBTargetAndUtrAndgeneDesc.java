	import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;


	public class miRDBTargetAndUtrAndgeneDesc {

		private InputStream F_in1, F_in2;
		private BufferedReader Br1, Br2;
		private FileWriter Fw;
		private BufferedWriter Bw;
		
		public void OpenFile() throws IOException
		{
			
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/CombineTargetAndUtr.txt");
			F_in2 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/geneDesc.txt");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/miRDB_data/CombineTargetAndUtrAndGeneDesc.txt"));
			Br1 = new BufferedReader(new InputStreamReader(F_in1, Charset.forName("UTF-8")));
			Br2 = new BufferedReader(new InputStreamReader(F_in2, Charset.forName("UTF-8")));
			Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br1.close();
			Bw.close();
			Fw.close();
			Br2.close();
			F_in1 = null;
			F_in2 = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			int targetAndUtrCount = 0;
			String targetAndUtr[] = new String[2105008];
			String targetAndUtrColumn3[] = new String[2105008];
			String geneDescColumn1, geneDescColumn2, geneDescColumn3, geneDescColumn4, geneDescColumn5, geneDescColumn6;
			String line;
			OpenFile();
			System.out.println("Start:");
			while((line = Br1.readLine()) != null)
			{
				targetAndUtr[targetAndUtrCount] = line;
				String split[] = line.split("\t");
				targetAndUtrColumn3[targetAndUtrCount] = split[2];
				targetAndUtrColumn3[targetAndUtrCount] = targetAndUtrColumn3[targetAndUtrCount].trim();
				targetAndUtrCount = targetAndUtrCount + 1;
			}
			while((line = Br2.readLine()) != null)
			{
				String split[] = line.split("\t");
				geneDescColumn1 = split[0];
				geneDescColumn2 = split[1];
				geneDescColumn3 = split[2];
				geneDescColumn4 = split[3];
				geneDescColumn5 = split[4];
				geneDescColumn6 = split[5];
				geneDescColumn2 = geneDescColumn2.trim();
				for(int i = 0; i < 2105008; i++)
				{
					if(geneDescColumn2.equals(targetAndUtrColumn3[i]))
					{
						Bw.write(targetAndUtr[i] + "\t" + geneDescColumn1 + "\t" + geneDescColumn3 + "\t" + geneDescColumn4 + "\t" + geneDescColumn5 + "\t" + geneDescColumn6 + "\n");
						targetAndUtr[i] = "";
						break;
					}
				}
			}
			for(int j = 0; j < 2105008; j++)
			{
				if(!targetAndUtr[j].isEmpty())
				{
					Bw.write(targetAndUtr[j] + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\n");
				}
			}
			CloseFile();
			System.out.println("Completed");
		}
	}
