import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;


public class CleaningHavemiRNAFamily {

	private InputStream F_in;
	private BufferedReader Br;
	private FileWriter Fw;
	private BufferedWriter Bw;
	private int Count;
	
	public void OpenFile() throws IOException
	{
		
		F_in = new FileInputStream("C:/Users/He Zhang/Desktop/Pragramming/miRNAandmiRNAFamily.doc");
		Fw = new FileWriter(new File("C:/Users/He Zhang/Desktop/Pragramming/OnlymiRNAandFamily.doc"));
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
		String Human = "hsa-";
		Count = 0;
		OpenFile();
		while((line = Br.readLine()) != null)
		{
			if(line.indexOf(Human) > 0)
			{
				line = line.substring(0, line.length()-1);
				Bw.write(line + "\n");
				Count = Count + 1;
			}
		}
		CloseFile();
		System.out.println(Count);
	}
}
