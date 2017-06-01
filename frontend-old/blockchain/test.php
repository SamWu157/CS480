<?php
echo "<h1>Information About the Survey Blockchain</h1>";

$a = 'curl -s --user multichainrpc:7XxgB2FjyPscjUgX3VxUpbr5h4MX3QsL52LU4KDQdjiY --data-binary \'';
$b = '{"jsonrpc": "1.0", "id":"curltest", "method": "liststreamitems", "params": ["test", false';
$c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:6798/';
$cmd = $a . $b . $c;

echo "\n<h2>Raw Output</h2><pre>\n";
$ret=system($cmd);

echo "\n<h2>Decoded Output</h2>\n";
$rets = json_decode($ret, true);
print_r($rets);

echo "\n<h2>Single-Item Output</h2>\n";
?>
rpcuser=multichainrpc
rpcpassword=
