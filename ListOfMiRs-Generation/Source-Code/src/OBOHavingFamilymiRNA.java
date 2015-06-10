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


	public class OBOHavingFamilymiRNA {

		private InputStream F_in, F_in1, F_in2;
		private BufferedReader Br, Br1, Br2;
		private FileWriter Fw;
		private BufferedWriter Bw;
		private int Count, number;
		
		public void OpenFile() throws IOException
		{
			
			F_in = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/OnlymiRNAandFamily.doc");
			F_in1 = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/HavingmiRNAFamily-miRNA.doc");
			F_in2 = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/MiRNAGeneFamily.obo");
			Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/miRNA-HavingFamily.obo"));
			Br = new BufferedReader(new InputStreamReader(F_in, Charset.forName("UTF-8")));
			Br1 = new BufferedReader(new InputStreamReader(F_in1, Charset.forName("UTF-8")));
			Br2 = new BufferedReader(new InputStreamReader(F_in2, Charset.forName("UTF-8")));
			Bw = new BufferedWriter(Fw);
		}
		
		public void CloseFile() throws IOException 
		{
			Br.close();
			Bw.close();
			Fw.close();
			Br1.close();
			Br2.close();
			F_in = null;
			F_in1 = null;
			F_in2 = null;
		}
		
		public void CountNumberOfGene() throws IOException
		{
			Vector<String> a = new Vector<String>();
			Vector<String> b = new Vector<String>();
			String line, term, id, AC = "";
			String str1 = "", str2 = "";
			String ID = "id: ncro", name = "name: ";
			Count = 1667;
			number = 0;
			OpenFile();
			while((line = Br2.readLine()) != null)
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
					b.add(line);
				}
				if(str2.equals(name))
				{
					line = line.substring(6, line.length());
					b.add(line);
				}
			}
			for(int y = 0; y < b.size(); y++)
				//System.out.println(b.elementAt(y).toString());
			while((line = Br1.readLine()) != null)
			{
				a.add(line);
			}
			for(int o = 0; o < a.size(); o++)
				//System.out.println(a.elementAt(o).toString());
			while((line = Br.readLine()) != null)
			{
				term = "";
				
				String miRNA[] = line.split("\t");
				miRNA[0] = miRNA[0].substring(5, miRNA[0].length());
				miRNA[1] = miRNA[1].substring(5, miRNA[1].length());
				//for(int i = 0; i < miRNA.length; i++)
					//System.out.println(miRNA[i]);
				for(int t = 0; t < b.size(); t++)
				{
					
					if(b.elementAt(t).equals(miRNA[1].toString()))
					{
						System.out.println(b.size());
						str1 = b.elementAt(t-1).toString();
						str2 = b.elementAt(t).toString();
						break;
					}
				}
				for(int j = 2; j < miRNA.length; j++)
				{
					for(int t = 0; t < a.size(); t++)
					{
						if(a.elementAt(t).indexOf(miRNA[j].toString()) > 0)
						{
							AC = a.elementAt(t).substring(0, 9);
						}
					}
					
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
					term = term + "name: " + miRNA[j] + "\n";
					term = term + "xref: miRBase:" + AC + "\n";
					term = term + "is_a: ncro:0000810 ! HumanMiRNA" + "\n";
					term = term + "relationship: ncro:isClassifiedIntoGeneFamilyGroup " + str1 + " ! " + str2 + "\n";
					Bw.write(term + "\n");
					
					number = number + 1;
				}
			}
			CloseFile();
			System.out.println(number);
		}
	}
