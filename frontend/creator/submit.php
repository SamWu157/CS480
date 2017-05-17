<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="A polling app which allows users to create polls and vote on polls created by other users. Utilizes blockchain to store results.">
    <meta name="author" content="Quang Bui, Vu Dao, Ryan Gunawan, Brian Sun, Samantha Wu">

    <title>CS480: Blockchain Poll</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">

    <!--CSS -->
    <link href="other.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="../index.html">
                        Blockchain Poll
                    </a>
                </li>
                <li>
                    <a href="#">Create</a>
                </li>
                <li>
                    <a href="../voter/index.php">Vote</a>
                </li>
                <li>
                    <a href="../results/index.php">Results</a>
                </li>
                <li>
                    <a href="../about/index.html">Team</a>
                </li>

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Poll Created</h1>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                    </div>
                </div>
                <?php
                $servername = "localhost";
                $username = "cs480";
                $password = "password";
                $database = "polls";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error());
                }

                $poll_name = $_POST["poll_name"];
                $creator = $_POST["creator"]; 
                $counter = $_POST["counter"];
                $table = "`" . $creator . ":" . $poll_name . "`";
                $voter_table = "`vote:" . $creator . ":" . $poll_name . "`";
                $option = array();
                $valid = true;

                // check creator id
                if (empty($creator)){
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Creator ID cannot be empty";
                    echo "</div>";
                } elseif (!ctype_alnum(str_replace(' ', '', $creator))) {
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Creator ID can only contain alphanumeric characters";
                    echo "</div>";
                }

                // check poll name
                if (empty($poll_name)){
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Poll Name cannot be empty";
                    echo "</div>";
                } elseif (!ctype_alnum(str_replace(' ', '', $poll_name))) {
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Poll name can only contain alphanumeric characters";
                    echo "</div>";
                }

                // get/check options
                for ($x = 1; $x <= $counter; $x++) {
                    $current = $_POST["option_" . (string)$x];
                    if (empty($current)) {
                        echo "<div class='row buffer'>";
                        echo "<b>Error: </b>Option cannot be empty";
                        echo "</div>";
                        $valid = false;
                        break;
                    } elseif (in_array($current, $option)) {
                        echo "<div class='row buffer'>";
                        echo "<b>Error: </b>Options cannot be the same";
                        echo "</div>";
                        $valid = false;
                    } elseif (!ctype_alnum(str_replace(' ', '', $current))) {
                        $valid = false;
                        echo "<div class='row buffer'>";
                        echo "<b>Error: </b>Options can only contain alphanumeric characters";
                        echo "</div>";
                        break;
                    } else {
                        $option[] = $current;
                    }
                }

                // create tables
                if ($valid) {
                    echo "<div class='row buffer'>";

                    // create poll table
                    $sql = "CREATE TABLE " . $table . " (id TEXT, opt TEXT)";

                    if ($conn->query($sql) === FALSE) {
                        echo "<b>Error creating tables</b>: " . $conn->error; 
                    } else {
                        // add to id table
                        $sql = "INSERT INTO creators (id, poll) VALUES ('" . $creator . "', '" . $poll_name . "')"; 

                        if ($conn->query($sql) === TRUE) {
                            // init table
                            foreach ($option as $o) {
                                $hash_o = hash("md5", $o);
                                $sql = "INSERT INTO " . $table . "(id, opt) VALUES ('" . $hash_o . "', '" . $o . "')";
                                $conn->query($sql);
                            }
                            // success
                            echo "<b>Poll Created: </b>" . $poll_name;
                        } else {
                            echo "<b>Error creating table</b>: " . $conn->error;
                        }

                        // create voters table
                        $sql = "CREATE TABLE " . $voter_table . " (id TEXT)";
                        if (!($conn->query($sql))) {
                            echo "<b>Error creating table</b>: " . $conn->error;
                        }
                    }

                    echo "</div>";

                    // create block stream
                    // may need to edit port and password
                    //
                    $port = trim(file_get_contents('../config/port'));//'6750';
                    $rpcusername = trim(file_get_contents('../config/rpc_username'));//'multichainrpc';
                    $rpcpassword = trim(file_get_contents('../config/rpc_password'));//'EktTXuc9EP3nKVD2GZVW2JJdxBVUTswc1YfC1mFpino2';
                    $a = 'curl -s --user ' . $rpcusername . ':' . $rpcpassword . ' --data-binary \'';
                    $b = '{"jsonrpc": "1.0", "id":"curltest", "method": "create", "params": ["stream", "' . $poll_name . '", false';
                    $c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:' . $port . '/';
                    $cmd = $a . $b . $c;

                    $ret = system($cmd); 
                    //$rets = json_decode($ret, true);
                    //
                    // subscribe to stream
                    $a = 'curl -s --user ' . $rpcusername . ':' . $rpcpassword . ' --data-binary \'';
                    $b = '{"jsonrpc": "1.0", "id":"curltest", "method": "subscribe", "params": ["' . $poll_name . '", false';
                    $c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:' . $port . '/';
                    $cmd = $a . $b . $c;
                    $ret = system($cmd); 

                }
                echo "<div class='row buffer'>" .
                    "<input type='button' value='back' class='buffer' onClick=window.location='../index.html'>" .
                    "<input type='button' value='create another' class='buffer' onClick=window.location='index.html'></div>";
                $conn->close();
                ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
