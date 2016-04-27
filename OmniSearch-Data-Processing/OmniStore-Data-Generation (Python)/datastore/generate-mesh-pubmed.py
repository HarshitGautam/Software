import json, time, urllib.request

# Open input and output files
f1 = open('input/mesh-list.txt', 'r')
f2 = open('output/mesh_pubmed.ttl', 'w')

# Write prefix
f2.write('@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')

# Local variables
start = time.process_time()
retmax = 10000
i = 1

# Loop through all mesh terms
for line in f1:
    # Reset starting index
    retstart = 0

    # Get the mesh term
    tokens = line.strip().split('\t')
    mesh = tokens[0].replace(' ', '%20')

    # Loop
    while True:
        # Wait until 1/3 of a second has passed
        # No more than 3 queries per second is allowed by the NCBI API.
        while(time.process_time() - start < 0.34): pass
        start = time.process_time()

        # Query url to get pubmed ids for a particular mesh term
        url = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=%22' + mesh + '%22[MeSH%20Terms]&retstart=' + str(retstart) + '&retmax=' + str(retmax) + '&retmode=json'
        try:
            # Send the request to NCBI
            response = urllib.request.urlopen(url)
        except:
            # Upon error, wait 3 seconds and retry
            print('Retrying ' + mesh + ' in 3 seconds.')
            time.sleep(3)
            continue
        # Parse response into json object
        data = json.loads(response.read().decode('utf-8'))

        # Loop through the pubmed ids
        for pmid in data['esearchresult']['idlist']:
            # Write triple to file
            f2.write('obo:' + tokens[1] + ' obo:OMIT_0000151 "' + pmid + '" .\n')

        # Increment the starting index
        retstart += retmax
        # If all pubmed ids have been retrieved
        if(retstart > int(data['esearchresult']['count'])):
            # Exit the loop
            break

    # Print count
    print(str(i))
    i += 1
    
f1.close()
f2.close()
