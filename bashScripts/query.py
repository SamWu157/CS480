# Queries multichain blockchain
#	Usage: python query.py "chain-name"

import json
import sys
import subprocess

bashCmd = "multichain-cli" + sys.argv[1] + "liststreamitems poll"
proc = subprocess.Popen(bashCmd.split(), stdout=subprocess.PIPE)
output, error = process.communicate()
parsed_json = json.loads(output)

for a in range(0, len(parsed_json)):
	print parsed_json[a]["key"] , " : " , parsed_json[a]["data"]
	# insert hashes from blockchain to temp variable to verify
	hashes[a] = parsed_json[a]["data"]

## need to add additional parsing functionality?
# verify that supplied hashes are valid with the blockchain data
inputData = sys.argv[2]

for a in range(0, len(hashes)):
    if(hashes[a] != inputData[a]):
		print "INVALID HASH | Key: ", parsed_json[a]["key"] , " | ", hashes[a], " != ", inputData[a]
