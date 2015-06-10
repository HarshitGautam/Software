	import java.io.BufferedReader;
	import java.io.BufferedWriter;
	import java.io.File;
	import java.io.FileInputStream;
	import java.io.FileWriter;
	import java.io.IOException;
	import java.io.InputStream;
	import java.io.InputStreamReader;
	import java.nio.charset.Charset;

	public class ExtractingHavemiRNAFamily {
		private InputStream F_in;
		private BufferedReader Br;
		private FileWriter Fw;
		private BufferedWriter Bw;
		private int Count;
		
		
		public void OpenFile() throws IOException
		{
			
			F_in = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/miFam.dat");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/miRNAandmiRNAFamily.doc"));
			Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
			Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br.close();
			Bw.close();
			Fw.close();
			Br = null;
			F_in = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			String line;
			String AC = "AC   ", ID = "ID   ", Human = " hsa-";
			String hu = "ID   hsa-";
			String subLine = "";
			Count = 0;
			OpenFile();
			while((line = Br.readLine()) != null)
			{
				if(line.length() > 5)
				{
					subLine = line.substring(0, 5);
					//subLine = line.substring(0, 9);
				}
				else
					subLine = "";
				if(subLine.equals(AC))
				{
					Bw.write("\n");
					Bw.write(line + "\t");
				}
				else if(subLine.equals(ID))
				{
					Bw.write(line + "\t");
				}
				if(line.indexOf(Human) > 0)
				{
					line = line.substring(16, line.length());
					Bw.write(line + "\t");
					Count = Count + 1;
				}
			}
			System.out.println(Count);
			CloseFile();
		}
	}

