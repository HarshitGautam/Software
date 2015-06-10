	import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;


	public class miRDBAll {

		private InputStream F_in1, F_in2;
		private BufferedReader Br1, Br2;
		private FileWriter Fw;
		private BufferedWriter Bw;
		
		public void OpenFile() throws IOException
		{
			
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/CombineTargetAndUtrAndGeneDesc.txt");
			F_in2 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/mirna_mature.txt");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/miRDB_data/Combination.txt"));
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
			String targetAndUtrColumn1[] = new String[2105008];
			String mirna_matureColumn2;
			String line;
			OpenFile();
			System.out.println("Start:");
			while((line = Br1.readLine()) != null)
			{
				targetAndUtr[targetAndUtrCount] = line;
				String split[] = line.split("\t");
				targetAndUtrColumn1[targetAndUtrCount] = split[2];
				targetAndUtrColumn1[targetAndUtrCount] = targetAndUtrColumn1[targetAndUtrCount].trim();
				targetAndUtrCount = targetAndUtrCount + 1;
			}
			Bw.write("targetID" + "@@@" + "mirID" + "@@@" + "geneID" + "@@@" + "accession" + "@@@" + "score" + "@@@" + "sequence" + "@@@" + "taxID" + "@@@" + "symbol" + "@@@" + "mim_id" + "@@@" + "mirbase_acc" + "@@@" + "description" + "@@@" + "auto_mature" + "@@@" + "mature_name" + "@@@" + "previous_mature_id" + "@@@" + "mature_acc" + "@@@" + "evidence" + "@@@" + "experiment" + "@@@" + "similarity" + "***********************" + "\n");
			while((line = Br2.readLine()) != null)
			{
				String split[] = line.split("\t");
				mirna_matureColumn2 = split[1];
				mirna_matureColumn2 = mirna_matureColumn2.trim();
				for(int i = 0; i < 2105008; i++)
				{
					if(mirna_matureColumn2.equals(targetAndUtrColumn1[i]))
					{
						line = line.replace(mirna_matureColumn2 + "\t", "");
						String str = targetAndUtr[i] + "\t" + line;
						String spli[] = str.split("\t");
						for(int p = 0; p < spli.length; p++)
						{
							Bw.write(spli[p]);
							if(p == spli.length-1)
								Bw.write("***********************" + "\n");
							else
								Bw.write("@@@");
						}
						targetAndUtr[i] = "";
						break;
					}
				}
			}
			for(int j = 0; j < 2105008; j++)
			{
				if(!targetAndUtr[j].isEmpty())
				{
					String str = targetAndUtr[j] + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL" + "\t" + "NULL"  + "\t" + "NULL";
					String spli[] = str.split("\t");
					for(int p = 0; p < spli.length; p++)
					{
						Bw.write(spli[p]);
						if(p == spli.length-1)
							Bw.write("***********************" + "\n");
						else
							Bw.write("@@@");
					}
				}
			}
			CloseFile();
			System.out.println("Completed");
		}
	}
