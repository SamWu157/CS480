# Queries multichain blockchain
#	Usage: python query.py "chain-name"

import json
import sys
import subprocess

bashCmd = "multichain-cli" + sys.argv[1] + "liststreamitems poll"
proc = subprocess.Popen(bashCommand.split(), stdout=subprocess.PIPE)
output, error = process.communicate()
parsed_json = json.loads(output)

for a in range(0, len(parsed_json)):
	print parsed_json[a]["key"] , " : " , parsed_json[a]["data"]
