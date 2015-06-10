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


	public class OBOmiRNAFamily {

		private InputStream F_in, F_in1;
		private BufferedReader Br, Br1;
		private FileWriter Fw;
		private BufferedWriter Bw;
		private int Count, number;
		public Vector<String> a = new Vector<String>();
		public void OpenFile() throws IOException
		{
			
			F_in = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/OnlymiRNAandFamily.doc");
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/miRNA-HavingFamily.obo");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/MiRNAGeneFamily.obo"));
			Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
			Br1 = new BufferedReader(new InputStreamReader(F_in1, Charset.forName("UTF-8")));
			Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br.close();
			Bw.close();
			Fw.close();
			Br = null;
			F_in = null;
			Br1 = null;
			F_in1 = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			String line, term, id, AC = "";
			Count = 2692;
			number = 0;
			OpenFile();
			
			String ID = "id: ncro", name = "name: ";
			String str1 = "", str2 = "";
			while((line = Br1.readLine()) != null)
			{
				if(line.length() > 9)
					str1 = line.substring(0, 8);
				else
					str1 = "";
				if(line.length() > 6)
					str2 = line.substring(0, 6);
				else
					str2 = "";
				if(str1.equals(ID))
				{
					line = line.substring(4, line.length());
					a.add(line);
				}
				if(str2.equals(name))
				{
					line = line.substring(6, line.length());
					a.add(line);
				}
					
			}
			while((line = Br.readLine()) != null)
			{
				term = "";
				String miRNA[] = line.split("\t");
				System.out.println(miRNA[0]);
				miRNA[0] = miRNA[0].substring(5, miRNA[0].length());
				miRNA[1] = miRNA[1].substring(5, miRNA[1].length());
				//System.out.println(miRNA[1]);
				term = "";
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
				id = "id: ncro:" + id + "\n";
				term = "[Term]" + "\n";
				term = term + id;
				term = term + "name: " + miRNA[1] + "\n";
				term = term + "xref: miRBase:" + miRNA[0] + "\n";
				term = term + "is_a: ncro:0001667 ! MiRNAGeneFamily" + "\n";
				for(int j = 2; j < miRNA.length; j++)
				{
					for(int t = 0; t < a.size(); t++)
					{
						if(a.elementAt(t).equals(miRNA[j].toString()))
						{
							str1 = a.elementAt(t-1).toString();
							str2 = a.elementAt(t).toString();
							break;
						}
					}
					
					term = term + "relationship: ncro:isAboutGroupedMiRNA " + str1 + " ! " + str2 + "\n";
					number = number + 1;
				}
				Bw.write(term + "\n");
				//for(int q = 0; q < a.size(); q++)
				//	System.out.println(a.elementAt(q));
			}
			CloseFile();
			System.out.println(number);
		}
	}
