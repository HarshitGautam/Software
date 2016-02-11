using System;
using System.Collections.Generic;
using System.IO;

namespace GeneData
{
    class Program
    {
        // Holds a list of miRNAs, Genes and MeSH Terms
        static List<MiRNA> mirnas = new List<MiRNA>();
        static List<Gene> genes = new List<Gene>();
        static List<MeshTerm> meshTerms = new List<MeshTerm>();

        // Holds starting OMIT IRI
        static int omit_iri = 200;

        static StreamReader sr1, sr2, sr3, sr4, sr5, sr6;
        static StreamWriter sw1, sw2, sw3, sw4, sw5, sw6;

        static void Main(string[] args)
        {
            // text_data path
            string input_path = "text_data/";

            // mirna_data.txt should contain the following columns separated by a tab
            // Label    IRI
            // 
            // Example mirna_data.txt:
            // hsa-let-7a-2-3p	NCRO_MIMAT0010195
            sr1 = new StreamReader(new FileStream(input_path + "mirna_data.txt", FileMode.Open));

            // mirdb_data.txt should contain the following columns separated by a tab
            // microRNA     GeneId      GeneSymbol      Score
            // 
            // Example mirdb_data.txt:
            // hsa-miR-125b-5p	3662	IRF4	100
            sr2 = new StreamReader(new FileStream(input_path + "mirdb_data.txt", FileMode.Open));

            // targetscan_data.txt should contain the following columns separated by a tab
            // microRNA     GeneId      GeneSymbol      Score
            // 
            // Example targetscan_data.txt:
            // hsa-miR-125b-5p	3662	IRF4	100
            sr3 = new StreamReader(new FileStream(input_path + "targetscan_data.txt", FileMode.Open));

            // miranda_data.txt should contain the following columns separated by a tab
            // microRNA     GeneId      GeneSymbol      Score
            // 
            // Example miranda_data.txt:
            // hsa-miR-125b-5p	3662	IRF4	100
            sr4 = new StreamReader(new FileStream(input_path + "miranda_data.txt", FileMode.Open));

            // pubmed_data.txt should contain the following columns separated by a tab
            // GeneId   PubMedId    MeSH_Term
            // 
            // Example pubmed_data.txt:
            // 3662 19897031	Mutagenesis
            sr5 = new StreamReader(new FileStream(input_path + "pubmed_data.txt", FileMode.Open));

            // mesh_term_data.txt should contain the following columns separated by a tab
            // MeSH_Term    OMIT_IRI
            // 
            // Example mesh_term_data.txt:
            // Drug resistance  OMIT_0000072
            sr6 = new StreamReader(new FileStream(input_path + "mesh_term_data.txt", FileMode.Open));

            // If the output directory does not exist
            if(!Directory.Exists("output"))
            {
                // Create the directory
                Directory.CreateDirectory("output");
            }

            // rdf_data path
            string output_path = "output/";

            // Create owl files for writing
            sw1 = new StreamWriter(new FileStream(output_path + "mirna.ttl", FileMode.Create));
            sw2 = new StreamWriter(new FileStream(output_path + "genes.ttl", FileMode.Create));
            sw3 = new StreamWriter(new FileStream(output_path + "mirdb.ttl", FileMode.Create));
            sw4 = new StreamWriter(new FileStream(output_path + "targetscan.ttl", FileMode.Create));
            sw5 = new StreamWriter(new FileStream(output_path + "miranda.ttl", FileMode.Create));
            sw6 = new StreamWriter(new FileStream(output_path + "pubmed.ttl", FileMode.Create));

            // Construct RDF Header
            string header =
                "@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\r\n" +
                "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .\r\n" +
                "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .\r\n" +
                "@prefix obo: <http://purl.obolibrary.org/obo/> .\r\n\r\n";

            // Write header to output files
            sw1.WriteLine(header);
            sw2.WriteLine(header);
            sw3.WriteLine(header);
            sw4.WriteLine(header);
            sw5.WriteLine(header);
            sw6.WriteLine(header);

            // Show status
            Console.WriteLine("Loading miRNAs...");

            // While miRNA data is available
            while (!sr1.EndOfStream)
            {
                // Read line and split into Label and IRI
                String[] tokens = sr1.ReadLine().Split("\t".ToCharArray());

                // Get the Label and IRI
                String label = tokens[0];
                String iri = tokens[1];

                // Add miRNA to list
                mirnas.Add(new MiRNA(iri, label));
            }

            // Show status
            Console.WriteLine("Loading MeSH Terms...");

            // While MeSH Term data is available
            while (!sr6.EndOfStream)
            {
                // Read line and split into IRI and Label
                String[] tokens = sr6.ReadLine().Split("\t".ToCharArray());

                // Get the IRI and Label
                String iri = tokens[1];
                String label = tokens[0];

                // Add MeSH Term to list
                meshTerms.Add(new MeshTerm(iri, label));
            }

            // Show status
            Console.WriteLine("Generating miRDB Data...");

            // While miRDB.org data is available
            while (!sr2.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr2.ReadLine().Split("\t".ToCharArray());

                // Get the mirna
                MiRNA mirna = GetMiRNA(ln[0]);

                // Get the gene
                Gene gene = GetGene(ln[2], ln[1]);

                String iri = "obo:OMIT_" + omit_iri.ToString("D7");

                // Construct the miRDB.org triples
                String predTxt =
                    iri + " rdf:type obo:OMIT_0000020 .\r\n" +
                    iri + " obo:OMIT_0000159 obo:" + mirna.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000160 obo:" + gene.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000108 \"" + ln[3] + "\"^^xsd:integer .\r\n";

                // Increment the OMIT IRI
                ++omit_iri;

                // Write the Prediction to the mirdb.owl file 
                sw3.WriteLine(predTxt);
            }

            // Show status
            Console.WriteLine("Generating TargetScan Data...");

            // While targetscan.org data is available
            while (!sr3.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr3.ReadLine().Split("\t".ToCharArray());

                // Get the mirna
                MiRNA mirna = GetMiRNA(ln[0]);

                // Get the gene
                Gene gene = GetGene(ln[2], ln[1]);

                String iri = "obo:OMIT_" + omit_iri.ToString("D7");

                // Construct the targetscan.org triples
                String predTxt =
                    iri + " rdf:type obo:OMIT_0000019 .\r\n" +
                    iri + " obo:OMIT_0000159 obo:" + mirna.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000160 obo:" + gene.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000108 \"" + ln[3] + "\"^^xsd:integer .\r\n";

                // Increment the OMIT IRI
                ++omit_iri;

                // Write the Prediction to the targetscan.owl file
                sw4.WriteLine(predTxt);
            }

            // Show status
            Console.WriteLine("Generating miRanda Data...");

            // While microRNA.org data is available
            while (!sr4.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr4.ReadLine().Split("\t".ToCharArray());

                // Get the mirna
                MiRNA mirna = GetMiRNA(ln[0]);

                // Get the gene
                Gene gene = GetGene(ln[2], ln[1]);

                String iri = "obo:OMIT_" + omit_iri.ToString("D7");

                // Construct the miRanda.org triples
                String predTxt =
                    iri + " rdf:type obo:OMIT_0000021 .\r\n" +
                    iri + " obo:OMIT_0000159 obo:" + mirna.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000160 obo:" + gene.IRI + " .\r\n" +
                    iri + " obo:OMIT_0000108 \"" + ln[3] + "\"^^xsd:decimal .\r\n";

                // Increment the OMIT IRI
                ++omit_iri;

                // Write the Prediction to the targetscan.owl file
                sw5.WriteLine(predTxt);
            }

            // Show status
            Console.WriteLine("Generating PubMed Data...");

            // While pubmed data is available
            while (!sr5.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr5.ReadLine().Split("\t".ToCharArray());

                // Find the Gene object
                Gene gene = genes.Find(g => g.ID == ln[0]);

                // Find the MeSH Term object
                MeshTerm term = meshTerms.Find(m => m.Label == ln[2]);

                // If both were found
                if (gene != null && term != null)
                {
                    String iri = "obo:OMIT_" + omit_iri.ToString("D7");

                    // Construct the PubMed triples
                    String pmedTxt =
                        iri + " rdf:type obo:OMIT_0000003 .\r\n" +
                        iri + " obo:BFO_0000051 obo:" + term.IRI + " .\r\n" +
                        iri + " obo:OMIT_0000160 obo:" + gene.IRI + " .\r\n" +
                        iri + " obo:OMIT_0000151 \"" + ln[1] + "\"^^xsd:string .\r\n";

                    // Increment the OMIT IRI
                    ++omit_iri;

                    // Write the PubMed to the pubmed.owl file
                    sw6.WriteLine(pmedTxt);
                }
            }

            // Close all files
            sw1.Close();
            sw2.Close();
            sw3.Close();
            sw4.Close();
            sw5.Close();
            sw6.Close();
            sr1.Close();
            sr2.Close();
            sr3.Close();
            sr4.Close();
            sr5.Close();
            sr6.Close();
        }

        public static MiRNA GetMiRNA(string label)
        {
            // Get the miRNA from the list
            MiRNA mirna = mirnas.Find(m => m.Label == label);

            // If the miRNA was found
            if (mirna != null)
                // Return the miRNA
                return mirna;

            String iri = "OMIT_" + omit_iri.ToString("D7");

            // Construct the mirna triple
            String mirnaTxt =
                "obo:" + iri + " rdfs:subClassOf obo:NCRO_0000810 .\r\n" +
                "obo:" + iri + " rdfs:label \"" + label + "\"^^xsd:string .\r\n";

            // Create an instance of the new miRNA
            mirna = new MiRNA(iri, label);

            // Add the new miRNA to the miRNA list
            mirnas.Add(mirna);

            // Increment the OMIT IRI
            ++omit_iri;

            // Write the miRNA to the mirna.owl file
            sw1.WriteLine(mirnaTxt);

            // Return the miRNA
            return mirna;
        }

        public static Gene GetGene(string symbol, string id)
        {
            // Get the gene from the list
            Gene gene = genes.Find(g => g.Symbol == symbol);

            // If the gene was found
            if (gene != null)
                // Return the gene
                return gene;

            String iri = "OMIT_" + omit_iri.ToString("D7");

            // Construct the gene triple
            String geneTxt =
                "obo:" + iri + " rdf:type obo:NCRO_0000025 .\r\n" +
                "obo:" + iri + " rdfs:label \"" + symbol + "\"^^xsd:string .\r\n" +
                "obo:" + iri + " obo:OMIT_0000109 \"" + id + "\"^^xsd:string .\r\n";

            // Create an instance of the new gene
            gene = new Gene(iri, symbol, id);

            // Add the Gene to the Gene list
            genes.Add(gene);

            // Increment the OMIT IRI
            ++omit_iri;

            // Write the Gene to the genes.owl file
            sw2.WriteLine(geneTxt);

            // Return the gene
            return gene;
        }
    }

    /// <summary>
    ///  Used to store MeSH Term information
    /// </summary>
    class MeshTerm
    {
        public String IRI { get; set; }
        public String Label { get; set; }

        public MeshTerm(String iri, String label)
        {
            IRI = iri;
            Label = label;
        }
    }

    /// <summary>
    /// Used to store microRNA information
    /// </summary>
    class MiRNA
    {
        public String IRI { get; set; }
        public String Label { get; set; }

        public MiRNA(String iri, String label)
        {
            IRI = iri;
            Label = label;
        }
    }

    /// <summary>
    /// Used to store Gene information
    /// </summary>
    class Gene
    {
        public String IRI { get; set; }
        public String Symbol { get; set; }
        public String ID { get; set; }

        public Gene(String iri, String symbol, String id)
        {
            IRI = iri;
            Symbol = symbol;
            ID = id;
        }
    }
}
