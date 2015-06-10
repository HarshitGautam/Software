	import java.io.BufferedReader;
	import java.io.BufferedWriter;
	import java.io.File;
	import java.io.FileInputStream;
	import java.io.FileWriter;
	import java.io.IOException;
	import java.io.InputStream;
	import java.io.InputStreamReader;
	import java.nio.charset.Charset;


	public class miRDBTest {

		private InputStream F_in;
		private BufferedReader Br;
		private FileWriter Fw;
		private BufferedWriter Bw;
		
		public void OpenFile() throws IOException
		{
			F_in = new FileInputStream("C:/Users/He Zhang/Desktop/miRDB_data/Combination.txt");
			//Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/miRDB_data/1.txt"));
			Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
			//Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br.close();
			//Bw.close();
			//Fw.close();
			F_in = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			String line;
			int count = 0;
			OpenFile();
			System.out.println("Start:");
			while((line = Br.readLine()) != null)
			{
				//String split[] = line.split("\t");
				//split[5] = split[5].trim();
				//if(split[5].equals("NULL"))
					count = count + 1;
			}
			CloseFile();
			System.out.println(count);
		}
	}
