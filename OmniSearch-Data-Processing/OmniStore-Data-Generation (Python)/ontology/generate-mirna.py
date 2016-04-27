# mirna map
mirna = {}

# Starting omit id
id = 1000000

# Open mirbase raw data file
with open('mirna.txt', 'r') as f1:
    # Loop through each line
    for line in f1:
        # ID denotes mirna name
        if line.startswith('ID   hsa'):
            # Get the mirna name
            label = line.split('   ')[1].replace('-mir-', '-miR-')
            # Skip a line
            f1.readline()
            # Get the mirbase id
            dbxref = f1.readline().split('   ')[1].replace(';', '')
            # Add mirna name as key and mirbase id as value
            mirna[label] = dbxref
        # FT denotes a mirbase id
        elif line.startswith('FT                   /accession="'):
            # Get the mirbase id
            dbxref = line.split('"')[1]
            # Next line contains the mirna name
            label = f1.readline().split('"')[1].replace('-mir-', '-miR-')
            # If human mirna
            if label.startswith('hsa-'):
                # Add key value to map
                mirna[label] = dbxref

# Open mirna owl file
with open('mirna.owl', 'w') as f1:
    # Loop through mirna map
    for k, v in mirna.items():
        # Write mirna class to owl file
        f1.write('    <owl:Class rdf:about="&obo;NCRO_' + str(id).zfill(7) + '">\n')
        f1.write('        <rdfs:label rdf:datatype="&xsd;string">' + k.strip() + '</rdfs:label>\n')
        f1.write('        <rdfs:subClassOf rdf:resource="&obo;NCRO_0000810"/>\n')
        f1.write('        <oboInOwl:hasDbXref rdf:datatype="&xsd;string">miRBase:' + v.strip() + '</oboInOwl:hasDbXref>\n')
        f1.write('    </owl:Class>\n')
        # Increment omit id
        id += 1


