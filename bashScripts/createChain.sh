#!/bin/bash
# Creates a new blockchain in multichain and starts the daemon
# 	To run: ./createChain.sh "chain_name"
multichain-util create $1
multichaind $1 -daemon
multichain-cli create stream poll
