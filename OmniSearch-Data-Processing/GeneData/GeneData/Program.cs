using System;
using System.Collections.Generic;
using System.IO;

namespace Genes
{
    class Program
    {
        static void Main(string[] args)
        {
            // Holds a list of miRNAs, Genes and MeSH Terms
            List<MiRNA> mirnas = new List<MiRNA>();
            List<Gene> genes = new List<Gene>();
            List<MeshTerm> meshTerms = new List<MeshTerm>();

            // mirdb_data.txt should contain the following columns separated by a tab
            // microRNA     GeneId      GeneSymbol      Score
            // 
            // Example mirdb_data.txt:
            // hsa-miR-125b-5p	3662	IRF4	100
            StreamReader sr1 = new StreamReader(new FileStream("mirdb_data.txt", FileMode.Open));

            // targetscan_data.txt should contain the following columns separated by a tab
            // microRNA     GeneId      GeneSymbol      Score
            // 
            // Example targetscan_data.txt:
            // hsa-miR-125b-5p	3662	IRF4	100
            StreamReader sr2 = new StreamReader(new FileStream("targetscan_data.txt", FileMode.Open));

            // pubmed_data.txt should contain the following columns separated by a tab
            // GeneId   PubMedId    MeSH_Term
            // 
            // Example pubmed_data.txt:
            // 3662 19897031	Mutagenesis
            StreamReader sr3 = new StreamReader(new FileStream("pubmed_data.txt", FileMode.Open));

            // mesh_term_data.txt should contain the following columns separated by a tab
            // MeSH_Term    OMIT_IRI
            // 
            // Example mesh_term_data.txt:
            // Drug resistance  OMIT_0000072
            StreamReader sr4 = new StreamReader(new FileStream("mesh_term_data.txt", FileMode.Open));

            // Create owl files for writing
            StreamWriter sw1 = new StreamWriter(new FileStream("mirna.owl", FileMode.Create));
            StreamWriter sw2 = new StreamWriter(new FileStream("genes.owl", FileMode.Create));
            StreamWriter sw3 = new StreamWriter(new FileStream("mirdb.owl", FileMode.Create));
            StreamWriter sw4 = new StreamWriter(new FileStream("targetscan.owl", FileMode.Create));
            StreamWriter sw5 = new StreamWriter(new FileStream("pubmed.owl", FileMode.Create));

            // Construct RDF Header
            string header = "<?xml version=\"1.0\"?>\n" +
                "\t<!DOCTYPE rdf:RDF[\n" +
                "\t\t<!ENTITY owl \"http://www.w3.org/2002/07/owl#\">\n" +
                "\t\t<!ENTITY obo \"http://purl.obolibrary.org/obo/\">\n" +
                "\t\t<!ENTITY xsd \"http://www.w3.org/2001/XMLSchema#\">\n" +
                "\t\t<!ENTITY rdfs \"http://www.w3.org/2000/01/rdf-schema#\">\n" +
                "\t\t<!ENTITY rdf \"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">\n" +
                "\t]>\n" +
                "\t<rdf:RDF\n" +
                "\t\txmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n" +
                "\t\txmlns:owl=\"http://www.w3.org/2002/07/owl#\"\n" +
                "\t\txmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"\n" +
                "\t\txmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"\n" +
                "\t\txmlns:obo=\"http://purl.obolibrary.org/obo/\">\n";

            // Write header to output files
            sw1.WriteLine(header);
            sw2.WriteLine(header);
            sw3.WriteLine(header);
            sw4.WriteLine(header);
            sw5.WriteLine(header);

            // While MeSH Term data is available
            while (!sr4.EndOfStream)
            {
                // Read line and split into IRI and Label
                String[] tokens = sr4.ReadLine().Split("\t".ToCharArray());

                // Get the IRI and Label
                String iri = tokens[1];
                String label = tokens[0];

                // Add MeSH Term to list
                meshTerms.Add(new MeshTerm(iri, label));
            }

            // Holds starting OMIT IRI
            int i = 200;

            // While miRDB.org data is available
            while (!sr1.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr1.ReadLine().Split("\t".ToCharArray());

                // If the miRNA has not been generated
                if (mirnas.Find(m => m.Label == ln[0]) == null)
                {
                    // Construct the miRNA owl entry
                    String mirnaTxt = "\t<owl:Class rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                        "\t\t<rdfs:subClassOf rdf:resource=\"&obo;NCRO_0000810\"/>\n" +
                        "\t\t<rdfs:label rdf:datatype=\"&xsd;string\">" + ln[0] + "</rdfs:label>\n" +
                        "\t</owl:Class>\n";

                    // Add the new miRNA to the miRNA list
                    mirnas.Add(new MiRNA("OMIT_" + i.ToString("D7"), ln[0]));

                    // Increment the OMIT IRI
                    ++i;

                    // Write the miRNA to the mirna.owl file
                    sw1.WriteLine(mirnaTxt);
                }

                // If the Gene has not been generated
                if (genes.Find(g => g.Symbol == ln[2]) == null)
                {
                    // Construct the Gene owl entry
                    String geneTxt = "\t<owl:NamedIndividual rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                        "\t\t<rdf:type rdf:resource=\"&obo;NCRO_0000025\"/>\n" +
                        "\t\t<rdfs:label rdf:datatype=\"&xsd;string\">" + ln[2] + "</rdfs:label>\n" +
                        "\t\t<obo:OMIT_0000109 rdf:datatype=\"&xsd;string\">" + ln[1] + "</obo:OMIT_0000109>\n" +
                        "\t</owl:NamedIndividual>\n";

                    // Add the Gene to the Gene list
                    genes.Add(new Gene("OMIT_" + i.ToString("D7"), ln[2], ln[1]));

                    // Increment the OMIT IRI
                    ++i;

                    // Write the Gene to the genes.owl file
                    sw2.WriteLine(geneTxt);
                }

                // Find the Gene object
                Gene gene = genes.Find(g => g.Symbol == ln[2]);
                // If not found
                if (gene == null)
                {
                    // Throw an exception
                    throw new Exception("GENE MISSING");
                }

                // Find the miRNA object
                MiRNA mirna = mirnas.Find(m => m.Label == ln[0]);
                // If not found
                if (mirna == null)
                {
                    // Throw an exception
                    throw new Exception("MIRNA MISSING");
                }

                // Construct the miRDB.org Prediction owl entry
                String predTxt = "\t<owl:NamedIndividual rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                    "\t\t<rdf:type rdf:resource=\"&obo;OMIT_0000020\"/>\n" +
                    "\t\t<obo:OMIT_0000108 rdf:datatype=\"&xsd;int\">" + ln[3] + "</obo:OMIT_0000108>\n" +
                    "\t\t<obo:RO_0000057 rdf:resource=\"&obo;" + mirna.IRI + "\"/>\n" +
                    "\t\t<obo:RO_0000057 rdf:resource=\"&obo;" + gene.IRI + "\"/>\n" +
                    "\t</owl:NamedIndividual>\n";

                // Increment the OMIT IRI
                ++i;

                // Write the Prediction to the mirdb.owl file 
                sw3.WriteLine(predTxt);
            }

            // While targetscan.org data is available
            while (!sr2.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr2.ReadLine().Split("\t".ToCharArray());

                // If the miRNA has not been generated
                if (mirnas.Find(m => m.Label == ln[0]) == null)
                {
                    // Construct the miRNA owl entry
                    String mirnaTxt = "\t<owl:Class rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                        "\t\t<rdfs:subClassOf rdf:resource=\"&obo;NCRO_0000810\"/>\n" +
                        "\t\t<rdfs:label rdf:datatype=\"&xsd;string\">" + ln[0] + "</rdfs:label>\n" +
                        "\t</owl:Class>\n";

                    // Add the new miRNA to the miRNA list
                    mirnas.Add(new MiRNA("OMIT_" + i.ToString("D7"), ln[0]));

                    // Increment the OMIT IRI
                    ++i;

                    // Write the miRNA to the mirna.owl file
                    sw1.WriteLine(mirnaTxt);
                }

                // If the Gene has not been generated
                if (genes.Find(g => g.Symbol == ln[2]) == null)
                {
                    // Construct the Gene owl entry
                    String geneTxt = "\t<owl:NamedIndividual rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                        "\t\t<rdf:type rdf:resource=\"&obo;NCRO_0000025\"/>\n" +
                        "\t\t<rdfs:label rdf:datatype=\"&xsd;string\">" + ln[2] + "</rdfs:label>\n" +
                        "\t\t<obo:OMIT_0000109 rdf:datatype=\"&xsd;string\">" + ln[1] + "</obo:OMIT_0000109>\n" +
                        "\t</owl:NamedIndividual>\n";

                    // Add the Gene to the Gene list
                    genes.Add(new Gene("OMIT_" + i.ToString("D7"), ln[2], ln[1]));

                    // Increment the OMIT IRI
                    ++i;

                    // Write the Gene to the genes.owl file
                    sw2.WriteLine(geneTxt);
                }

                // Find the Gene object
                Gene gene = genes.Find(g => g.Symbol == ln[2]);
                // If not found
                if (gene == null)
                {
                    // Throw an exception
                    throw new Exception("GENE MISSING");
                }

                // Find the miRNA object
                MiRNA mirna = mirnas.Find(m => m.Label == ln[0]);
                // If not found
                if (mirna == null)
                {
                    // Throw an exception
                    throw new Exception("MIRNA MISSING");
                }

                // Construct the targetscan.org Prediction owl entry
                String predTxt = "\t<owl:NamedIndividual rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                    "\t\t<rdf:type rdf:resource=\"&obo;OMIT_0000019\"/>\n" +
                    "\t\t<obo:OMIT_0000108 rdf:datatype=\"&xsd;int\">" + ln[3] + "</obo:OMIT_0000108>\n" +
                    "\t\t<obo:RO_0000057 rdf:resource=\"&obo;" + mirna.IRI + "\"/>\n" +
                    "\t\t<obo:RO_0000057 rdf:resource=\"&obo;" + gene.IRI + "\"/>\n" +
                    "\t</owl:NamedIndividual>\n";

                // Increment the OMIT IRI
                ++i;

                // Write the Prediction to the targetscan.owl file
                sw4.WriteLine(predTxt);
            }

            // While pubmed data is available
            while (!sr3.EndOfStream)
            {
                // Read line and split by tab character
                String[] ln = sr3.ReadLine().Split("\t".ToCharArray());

                // Find the Gene object
                Gene gene = genes.Find(g => g.ID == ln[0]);
                // Find the MeSH Term object
                MeshTerm term = meshTerms.Find(m => m.Label.ToLower() == ln[2].ToLower());

                // If both were found
                if (gene != null && term != null)
                {
                    // Construct the PubMed owl entry
                    String pmidTxt = "\t<owl:NamedIndividual rdf:about=\"&obo;OMIT_" + i.ToString("D7") + "\">\n" +
                        "\t\t<rdf:type rdf:resource=\"&obo;OMIT_0000003\"/>\n" +
                        "\t\t<obo:BFO_0000051 rdf:resource=\"&obo;" + term.IRI + "\"/>\n" +
                        "\t\t<obo:OMIT_0000151 rdf:datatype=\"&xsd;string\">" + ln[1] + "</obo:OMIT_0000151>\n" +
                        "\t\t<obo:RO_0000057 rdf:resource=\"&obo;" + gene.IRI + "\"/>\n" +
                        "\t</owl:NamedIndividual>\n";

                    // Increment the OMIT IRI
                    ++i;

                    // Write the PubMed to the pubmed.owl file
                    sw5.WriteLine(pmidTxt);
                }
            }

            // Write the RDF Footer to the output files
            sw1.Write("\n</rdf:RDF>");
            sw2.Write("\n</rdf:RDF>");
            sw3.Write("\n</rdf:RDF>");
            sw4.Write("\n</rdf:RDF>");
            sw5.Write("\n</rdf:RDF>");

            // Close all files
            sw1.Close();
            sw2.Close();
            sw3.Close();
            sw4.Close();
            sw5.Close();
            sr1.Close();
            sr2.Close();
            sr3.Close();
            sr4.Close();
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
