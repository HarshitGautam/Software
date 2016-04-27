# OMIT id
id = 30001

# open the input and output files
with open('genes.txt', 'r') as f1, open('genes.owl', 'w') as f2:
    # read each line
    for line in f1:
        # split the line by tabs
        tokens = line.strip().split('\t')
        # write the rdf triple using the OMIT id, gene_symbol(1), gene_name(2) and Entrez id(0) to the output file
        f2.write('    <owl:Class rdf:about="&obo;OMIT_' + str(id).zfill(7) + '">\n')
        f2.write('        <rdfs:label rdf:datatype="&xsd;string">' + tokens[1] + '</rdfs:label>\n')
        f2.write('        <rdfs:comment rdf:datatype="&xsd;string">' + tokens[2] + '</rdfs:comment>\n')
        f2.write('        <oboInOwl:hasDbXref rdf:datatype="&xsd;string">Entrez:' + tokens[0] + '</oboInOwl:hasDbXref>\n')
        f2.write('        <rdfs:subClassOf rdf:resource="&obo;NCRO_0000025"/>\n')
        f2.write('    </owl:Class>\n')
        # increment the OMIT id
        id += 1
