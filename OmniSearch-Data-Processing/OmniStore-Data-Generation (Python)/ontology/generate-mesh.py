# Map from OMIT Id to MeSH term strings
omit2label = {
    1001:'Anatomy Category',
    1002:'Organisms Category',
    1003:'Diseases Category',
    1004:'Chemicals and Drugs Category',
    1005:'Analytical,Diagnostic and Therapeutic Techniques and Equipment Category',
    1006:'Psychiatry and Psychology Category',
    1007:'Phenomena and Processes Category',
    1008:'Disciplines and Occupations Category',
    1009:'Anthropology,Education,Sociology and Social Phenomena Category',
    1010:'Technology,Industry,Agriculture Category',
    1011:'Humanities Category',
    1012:'Information Science Category',
    1013:'Named Groups Category',
    1014:'Health Care Category',
    1015:'Publication Characteristics Category',
    1016:'Geographicals Category'
}

# Map from OMIT Id to parent MeSH Terms
omit2parents = {
    1001:[],
    1002:[],
    1003:[],
    1004:[],
    1005:[],
    1006:[],
    1007:[],
    1008:[],
    1009:[],
    1010:[],
    1011:[],
    1012:[],
    1013:[],
    1014:[],
    1015:[],
    1016:[]
}

# Map from MeSH term tree number to OMIT Id
treenum2omit = {
    'A':1001,
    'B':1002,
    'C':1003,
    'D':1004,
    'E':1005,
    'F':1006,
    'G':1007,
    'H':1008,
    'I':1009,
    'J':1010,
    'K':1011,
    'L':1012,
    'M':1013,
    'N':1014,
    'V':1015,
    'Z':1016
}

# Starting OMIT Id
id = 1017

# Open raw MeSH term data file
with open('mesh.txt', 'r') as f1:
    # Read each line
    for line in f1:
        # MH lines contain the MeSH term heading
        if line.startswith('MH ='):
            # Get the MeSH term heading and add to the map
            label = line.split('=')[1].strip()
            omit2label[id] = label
            omit2parents[id] = []
        # MN lines contain the MeSH term tree number
        if line.startswith('MN ='):
            # Get the tree number and add entry in the map
            treenum = line.split('=')[1].strip()
            treenum2omit[treenum] = id
            # If tree number contains a '.' then it has parents
            if '.' in treenum:
                # Get the parent
                treenum = treenum.rsplit('.', 1)[0]
            # Else it is a root MeSH term
            else:
                # Get the category number
                treenum = treenum[0]
            # Add the parent to the list
            omit2parents[id].append(treenum)
        # Line with a '*' denotes end of MeSH term entry
        if line.startswith('*'):
            # Increment the OMIT Id
            id += 1

# Create MeSH owl file
with open('mesh.owl', 'w') as f1:
    # Loop through the OMIT Id and MeSH term heading map
    for k, v in omit2label.items():
        # Write the MeSH term class to the owl file
        f1.write('    <owl:Class rdf:about="&obo;OMIT_' + str(k).zfill(7) + '">\n')
        f1.write('        <rdfs:label rdf:datatype="&xsd;string">' + v + '</rdfs:label>\n')
        # Get the parents
        parents = omit2parents.get(k)
        # If no parents
        if len(parents) == 0:
            # Root MeSH terms are a child of the MeSH_term class
            f1.write('        <rdfs:subClassOf rdf:resource="&obo;OMIT_0000110"/>\n')
        # Else
        else:
            # Loop through the parents
            for treenum in omit2parents.get(k):
                # Write subClassOf triple
                f1.write('        <rdfs:subClassOf rdf:resource="&obo;OMIT_' + str(treenum2omit.get(treenum)).zfill(7) + '"/>\n')
        f1.write('    </owl:Class>\n')
        
