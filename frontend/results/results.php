<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blockchain Poll - Poll Results</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/business-casual.css" rel="stylesheet">
    <link href="graph.css" rel="stylesheet">
    <link href="other.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="brand">Blockchain Poll</div>

    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand" href="index.html">Blockchain Poll</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="../index.html">Home</a>
                    </li>
                    <li>
                        <a href="../about/index.html">About</a>
                    </li>
                    <li>
                        <a href="../creator/index.html">Create</a>
                    </li>
                    <li>
                        <a href="../voter/index.php">Vote</a>
                    </li>
                    <li>
                        <a href="index.php">Results</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">
                        Poll <strong>Results</strong>
                    </h2>
                    <hr>
                </div>
                <div class="col-lg-12 text-center">
                    <br>

                    <?php
                    // get stream items
                    $port = trim(file_get_contents('../config/port'));
                    $rpcusername = trim(file_get_contents('../config/rpc_username'));
                    $rpcpassword = trim(file_get_contents('../config/rpc_password'));

                    $results = array();
                    $options = array();
                    $options_name = array();
                    // get option names
                    $servername = "localhost";
                    $username = "cs480";
                    $password = "password";
                    $database = "polls";

                    // create connection
                    $conn = new mysqli($servername, $username, $password, $database);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error());
                    }

                    $poll = $_GET["poll"];
                    $creator = $_GET["creator"];
                    $poll_name = $creator . ":" . $poll;
                    $poll_name = strtolower($poll_name);
                    $sql = "SELECT * FROM `" . $poll_name . "`";
                    $r = $conn->query($sql);
                    if ($r->num_rows > 0) {
                        while ($row = $r->fetch_assoc()) {
                            $id = $row["id"];
                            $opt = $row["opt"];
                            $options_name[$id] = $opt;
                            $results[$id] = 0;
                            $options[] = $id;
                        }
                    } else {
                        echo "<b>Error: </b> poll not found";
                    }

                    $conn->close();

                    $a = 'curl -s --user ' . $rpcusername . ':' . $rpcpassword . ' --data-binary \'';
                    $b = '{"jsonrpc": "1.0", "id":"curltest", "method": "liststreamitems", "params": ["' . $poll_name . '", false';
                    $c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:' . $port . '/';
                    $cmd = $a . $b . $c;

                    // parsing
                    $ret=exec($cmd);
                    $rets = json_decode($ret, true);
                    $r = $rets['result'];
                    foreach ($r as $x) {
                        $key = $x['key'];
                        $data = $x['data'];
                        /*
                        echo "<b>Key: </b>" . $key;
                        echo "<br>";
                        echo "<b>Data: </b>" . $data;
                        echo "<br><br>";
                         */
                        $results[$data] += 1;
                    }

                    /*
                    print_r($results);
                    echo "<br><br>";
                    print_r($options);
                    echo "<br><br>";
                    print_r($options_name);
                     */

                    $winner = array();
                    $size = count($winner);
                    foreach ($options as $o) {
                        if (empty($winner)) {
                            $winner[] = $o;
                        } else {
                            foreach ($winner as $win) {
                                if ($results[$o] > $results[$win]) {
                                    $winner[$size] = $o;
                                } else if ($results[$o] == $results[$win]) {
                                    $winner[] = $o;
                                    $size += 1;
                                }
                            }
                        }
                    }

                    echo "<h3>Winner</h3>";
                    // display winner
                    foreach ($winner as $w) {
                        $current = $options_name[$w];
                        echo "<h4>" . $current . "</h4>";
                    }

                    echo "<br><br>";

                    // table
                    echo "<table id='data-table' border='1' cellpadding='10' cellspacing='0'>";
                    echo "<caption>Vote Distribution</caption>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    foreach ($options as $o) {
                        $col = $options_name[$o];
                        echo '<th scope="col">' . $col . '</th>';
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<th scope='row'>Votes</th>";
                    foreach ($options as $o) {
                        $value = $results[$o];
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";

                    ?>


                    <br>
                    <br>
                    <img class="img-responsive img-border img-full" src="../img/results.jpg" alt="">
                    <hr>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Spring 2017</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>
