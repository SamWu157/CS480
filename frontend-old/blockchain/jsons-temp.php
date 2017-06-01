<?php
// create stream
$a = 'curl -s --user multichainrpc:C5qzsD5r6tmHPXDecvuev3ZdhwN6ukANMCMXb4TE9pFj --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "create", "params": ["stream", "stream_name", false';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:4334/';
$cmd = $a . $b . $c;

//liststream
$a = 'curl -s --user multichainrpc:C5qzsD5r6tmHPXDecvuev3ZdhwN6ukANMCMXb4TE9pFj --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "liststreams", "params":';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:4334/';
$cmd = $a . $b . $c;

//publish
$a = 'curl -s --user multichainrpc:C5qzsD5r6tmHPXDecvuev3ZdhwN6ukANMCMXb4TE9pFj --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "publish", "params": ["stream", "key", "data-hex"';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:4334/';
$cmd = $a . $b . $c;

// subscribe
$a = 'curl -s --user multichainrpc:C5qzsD5r6tmHPXDecvuev3ZdhwN6ukANMCMXb4TE9pFj --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "subscribe", "params": ["stream", false';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:4334/';
$cmd = $a . $b . $c;

// list stream items
$a = 'curl -s --user multichainrpc:C5qzsD5r6tmHPXDecvuev3ZdhwN6ukANMCMXb4TE9pFj --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "liststreamitems", "params": ["stream", false';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:4334/';
$cmd = $a . $b . $c;

// display json: need to parse
echo "\n<h2>Raw Output</h2><pre>\n";
$ret=system($cmd);
echo "\n<h2>Decoded Output</h2>\n";
$rets = json_decode($ret, true);
print_r($rets);

?>
