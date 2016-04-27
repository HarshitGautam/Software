import json, urllib.request

# Id conversion maps
accession2entrez = {}
mirna2ncro = {}
entrez2omit = {}
history2entrez = {}
ensembl2entrez = {}
symbol2omit = {}

# Holds missing ids
missing_accession = {}
missing_ensembl = {}
missing_entrez = {}
missing_mirna = {}

# Starting omit id
id = 100000

# Load all id mapping data
with open('mapping/accession2entrez.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        accession2entrez[tokens[0]] = tokens[1]

with open('mapping/ncro2mirna.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        mirna2ncro[tokens[1]] = tokens[0]

with open('mapping/omit2entrez.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        entrez2omit[tokens[1]] = tokens[0]

with open('mapping/entrez2history.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        history2entrez[tokens[1]] = tokens[0]

with open('mapping/entrez2ensembl.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        ensembl2entrez[tokens[1]] = tokens[0]

with open('mapping/omit2symbol.txt', 'r') as f1:
    for line in f1:
        tokens = line.strip().split('\t')
        symbol2omit[tokens[1]] = tokens[0]

# Open input and output files for mirdb
with open('input/mirdb.txt', 'r') as f1, open('output/mirdb.ttl', 'w') as f2:
    # Write prefixes
    f2.write('@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')
    # Read each line
    for line in f1:
        # Split by tab
        tokens = line.strip().split('\t')
        # If any micrornas or gene ids are missing,
        # add the missing fields to the appropriate
        # missing maps and continue
        ncro = mirna2ncro.get(tokens[0])
        if ncro is None:
            if missing_mirna.get(tokens[0]) is None:
                missing_mirna[tokens[0]] = ''
            continue
        entrez = accession2entrez.get(tokens[1])
        if entrez is None:
            if missing_accession.get(tokens[1]) is None:
                missing_accession[tokens[1]] = ''
            continue
        entrez = history2entrez.get(entrez, entrez)
        omit = entrez2omit.get(entrez)
        if omit is None:
            if missing_entrez.get(entrez) is None:
                missing_entrez[entrez] = ''
            continue
        # Get the new omit id
        iri = 'OMIT_' + str(id).zfill(7)
        id += 1
        # Write the triples to file
        f2.write('obo:' + iri + ' rdf:type obo:OMIT_0000020 .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000159 obo:' + ncro + ' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000160 obo:' + omit +' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000108 ' + tokens[2] + ' .\n')

# Open input and output files for targetscan
with open('input/targetscan.txt', 'r') as f1, open('output/targetscan.ttl', 'w') as f2:
    # Write prefixes
    f2.write('@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')
    # Read each line
    for line in f1:
        # Split by tab
        tokens = line.strip().split('\t')
        # If any micrornas or gene ids are missing,
        # add the missing fields to the appropriate
        # missing maps and continue
        ncro = mirna2ncro.get(tokens[0])
        if ncro is None:
            if missing_mirna.get(tokens[0]) is None:
                missing_mirna[tokens[0]] = ''
            continue
        entrez = ensembl2entrez.get(tokens[1])
        if entrez is None:
            if missing_ensembl.get(tokens[1]) is None:
                missing_ensembl[tokens[1]] = ''
            continue
        entrez = history2entrez.get(entrez, entrez)
        omit = entrez2omit.get(entrez)
        if omit is None:
            if missing_entrez.get(entrez) is None:
                missing_entrez[entrez] = ''
            continue
        # Get the new omit id
        iri = 'OMIT_' + str(id).zfill(7)
        id += 1
        # Write the triples to file
        f2.write('obo:' + iri + ' rdf:type obo:OMIT_0000019 .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000159 obo:' + ncro + ' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000160 obo:' + omit +' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000108 ' + tokens[2] + ' .\n')

# Open input and output files for miranda
with open('input/miranda.txt', 'r') as f1, open('output/miranda.ttl', 'w') as f2:
    # Write prefixes
    f2.write('@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')
    # Read each line
    for line in f1:
        # Split by tab
        tokens = line.strip().split('\t')
        # If any micrornas or gene ids are missing,
        # add the missing fields to the appropriate
        # missing maps and continue
        ncro = mirna2ncro.get(tokens[0])
        if ncro is None:
            if missing_mirna.get(tokens[0]) is None:
                missing_mirna[tokens[0]] = ''
            continue
        entrez = history2entrez.get(tokens[1], tokens[1])
        omit = entrez2omit.get(entrez)
        if omit is None:
            if missing_entrez.get(entrez) is None:
                missing_entrez[entrez] = ''
            continue
        # Get the new omit id
        iri = 'OMIT_' + str(id).zfill(7)
        id += 1
        # Write the triples to file
        f2.write('obo:' + iri + ' rdf:type obo:OMIT_0000021 .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000159 obo:' + ncro + ' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000160 obo:' + omit +' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000108 ' + tokens[2] + ' .\n')

# Open input and output files for mirtarbase
with open('input/mirtarbase.txt', 'r') as f1, open('output/mirtarbase.ttl', 'w') as f2:
    # Skip the first line
    f1.readline()
    # Write prefixes
    f2.write('@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix obo: <http://purl.obolibrary.org/obo/> .\n@prefix oboInOwl: <http://www.geneontology.org/formats/oboInOwl#> .\n\n')
    # Read each line
    for line in f1:
        # Split by tab
        tokens = line.strip().split('\t')
        # If any micrornas or gene ids are missing,
        # add the missing fields to the appropriate
        # missing maps and continue
        ncro = mirna2ncro.get(tokens[0])
        if ncro is None:
            if missing_mirna.get(tokens[0]) is None:
                missing_mirna[tokens[0]] = ''
            continue
        entrez = history2entrez.get(tokens[1], tokens[1])
        omit = entrez2omit.get(entrez)
        if omit is None:
            if missing_entrez.get(entrez) is None:
                missing_entrez[entrez] = ''
            continue
        # Get the new omit id
        iri = 'OMIT_' + str(id).zfill(7)
        id += 1
        # Write the triples to file
        f2.write('obo:' + iri + ' rdf:type obo:OMIT_0000174 .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000159 obo:' + ncro + ' .\n')
        f2.write('obo:' + iri + ' obo:OMIT_0000160 obo:' + omit +' .\n')
        f2.write('obo:' + iri + ' oboInOwl:hasDbXref "' + tokens[2] + '" .\n')

# Open input and output files for amigo go
with open('input/amigo.txt', 'r') as f1, open('output/amigo.ttl', 'w') as f2:
    # Write prefixes
    f2.write('@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')
    # Read each line
    for line in f1:
        # Split by tab
        tokens = line.strip().split('\t')
        # If the gene symbol is missing, continue
        omit = symbol2omit.get(tokens[0])
        if omit is None:
            continue
        # Write triple to file
        f2.write('obo:' + omit + ' obo:OMIT_0000169 ' + tokens[1] + ' .\n')

# Write all missing fields to their appropriate log files
with open('log/missing_accession.txt', 'w') as f1:
    for item in missing_accession:
        f1.write(item + '\n')

with open('log/missing_mirna.txt', 'w') as f1:
    for item in missing_mirna:
        f1.write(item + '\n')

with open('log/missing_entrez.txt', 'w') as f1:
    for item in missing_entrez:
        f1.write(item + '\n')

with open('log/missing_ensembl.txt', 'w') as f1:
    for item in missing_ensembl:
        f1.write(item + '\n')

