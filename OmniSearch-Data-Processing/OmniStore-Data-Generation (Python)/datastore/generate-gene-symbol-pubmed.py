import json, time, urllib.request

# Open input and output files
f1 = open('mapping/omit2symbol.txt', 'r')
f2 = open('output/gene_symbol_pubmed.ttl', 'w')

# Write prefix
f2.write('@prefix obo: <http://purl.obolibrary.org/obo/> .\n\n')

# Local variables
start = time.process_time()
retmax = 10000
i = 1

# Loop through all gene symbols
for line in f1:
    # Reset starting index
    retstart = 0

    # Get gene symbol
    tokens = line.strip().split('\t')
    symbol = tokens[1]

    # Loop
    while True:
        # Wait until 1/3 of a second has passed
        # No more than 3 queries per second is allowed by the NCBI API.
        while(time.process_time() - start < 0.34): pass
        start = time.process_time()

        # Query url to get pubmed ids for a particular gene symbol
        url = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=' + symbol + '&retstart=' + str(retstart) + '&retmax=' + str(retmax) + '&retmode=json'
        try:
            # Send the request to NCBI
            response = urllib.request.urlopen(url)
        except:
            # Upon error, wait 3 seconds and retry
            print('Retrying ' + symbol + ' in 3 seconds.')
            time.sleep(3)
            continue
        # Parse response into json object
        data = json.loads(response.read().decode('utf-8'))

        # Loop through the pubmed ids
        for pmid in data['esearchresult']['idlist']:
            # Write triple to file
            f2.write('obo:' + tokens[0] + ' obo:OMIT_0000151 "' + pmid + '" .\n')

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
