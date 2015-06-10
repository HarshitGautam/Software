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

	public class ExtractingNomiRNAFamily {
		private InputStream F_in1, F_in2;
		private BufferedReader Br1, Br2;
		private FileWriter Fw;
		private BufferedWriter Bw;
		private int Count;
		
		
		public void OpenFile() throws IOException
		{
			
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/miRNA.txt");
			F_in2 = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/miRNANoFamily.doc");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/NomiRNAFamily-miRNA.doc"));
			Br1 = new BufferedReader(new InputStreamReader(F_in1, Charset.forName("UTF-8")));
			Br2 = new BufferedReader(new InputStreamReader(F_in2, Charset.forName("UTF-8")));
			Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br1.close();
			Br2.close();
			Bw.close();
			Fw.close();
			F_in1 = null;
			F_in2 = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			Vector<String> miRNA = new Vector<String> ();
			String line1, line2, string;
			String subLine = "";
			
			OpenFile();
			while((line2 = Br2.readLine()) != null)
			{
				line2 = line2.trim();
				miRNA.add(line2);
			}
			Count = 0;
			while((line1 = Br1.readLine()) != null)
			{
				if(line1.length() > 23)
					line1 = line1.substring(0, 23);
				else
					line1 = "";
				line1 = line1.trim();
				for(int i = 0; i < miRNA.size(); i++)
				{
					String str = "ID   " + miRNA.elementAt(i);
					if(line1.equals(str))
					{
						string = Br1.readLine();
						string = Br1.readLine();
						Bw.write(string + "\t" + miRNA.elementAt(i) + "\n");
						Count = Count + 1;
						break;
					}
				}
			}
			System.out.println(Count);
			CloseFile();
		}
	}

