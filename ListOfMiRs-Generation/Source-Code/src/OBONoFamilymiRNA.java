	import java.io.BufferedReader;
	import java.io.BufferedWriter;
	import java.io.File;
	import java.io.FileInputStream;
	import java.io.FileWriter;
	import java.io.IOException;
	import java.io.InputStream;
	import java.io.InputStreamReader;
	import java.nio.charset.Charset;


	public class OBONoFamilymiRNA {

		private InputStream F_in;
		private BufferedReader Br;
		private FileWriter Fw;
		private BufferedWriter Bw;
		private int Count, number;
		
		public void OpenFile() throws IOException
		{
			
			F_in = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/NomiRNAFamily-miRNA.doc");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/miRNA-NoFamily.obo"));
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
			String line, term, id;
			Count = 810;
			number = 0;
			OpenFile();
			while((line = Br.readLine()) != null)
			{
				term = "";
				String miRNA[] = line.split("\t");
				miRNA[0] = miRNA[0].substring(0, miRNA[0].length()-1);
				//for(int i = 0; i < miRNA.length; i++)
					//System.out.println(miRNA[i]);
				Count = Count + 1;
				if(Count > 999)
				{
					id = Integer.toString(Count);
					id = "000" + id;
				}
				else
				{
					id = Integer.toString(Count);
					id = "0000" + id;
				}
				id = "id: OMIT:" + id + "\n";
				term = "[Term]" + "\n";
				term = term + id;
				term = term + "name: " + miRNA[1] + "\n";
				term = term + "xref: miRBase:" + miRNA[0] + "\n";
				term = term + "is_a: OMIT:0000810 ! HumanMiRNA" + "\n";
				Bw.write(term + "\n");
				number = number + 1;
			}
			CloseFile();
			System.out.println(number);
		}
	}
