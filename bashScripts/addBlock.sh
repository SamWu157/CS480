#!/bin/sh
# Adds a new block into the blockchain
#	Usage: ./addBlock.sh "key1" "data1"

multichain-cli publish poll $1 $2
multichain-cli liststreamitems poll


