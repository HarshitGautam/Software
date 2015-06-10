	import java.io.BufferedReader;
	import java.io.BufferedWriter;
	import java.io.File;
	import java.io.FileInputStream;
	import java.io.FileWriter;
	import java.io.IOException;
	import java.io.InputStream;
	import java.io.InputStreamReader;
	import java.nio.charset.Charset;


	public class miRDBDataBaseTargetAndUtr {

		private InputStream F_in1, F_in2;
		private BufferedReader Br1, Br2;
		private FileWriter Fw;
		private BufferedWriter Bw;
		
		public void OpenFile() throws IOException
		{
			
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/target.txt");
			F_in2 = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/utr.txt");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/miRDB_data/CombineTargetAndUtr.txt"));
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
			int targetCount = 0;
			String target[] = new String[2105008];
			String targetColumn4[] = new String[2105008];
			String utrColumn1, utrColumn2;
			String line;
			OpenFile();
			System.out.println("Start:");
			while((line = Br1.readLine()) != null)
			{
				target[targetCount] = line;
				String split[] = line.split("\t");
				targetColumn4[targetCount] = split[3];
				targetColumn4[targetCount] = targetColumn4[targetCount].trim();
				targetCount = targetCount + 1;
			}
			while((line = Br2.readLine()) != null)
			{
				String split[] = line.split("\t");
				split[0] = split[0].trim();
				utrColumn1 = split[0];
				utrColumn2 = split[1];
				for(int i = 0; i < 2105008; i++)
				{
					if(utrColumn1.equals(targetColumn4[i]))
					{
						Bw.write(target[i] + "\t" + utrColumn2 + "\n");
						target[i] = "";
						break;
					}
				}
			}
			for(int j = 0; j < 2105008; j++)
			{
				if(!target[j].isEmpty())
				{
					Bw.write(target[j] + "\t" + "NULL" + "\n");
				}
			}
			CloseFile();
			System.out.println("Completed");
		}
	}
