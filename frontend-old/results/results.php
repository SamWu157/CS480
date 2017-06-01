<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="A polling app which allows users to create polls and vote on polls created by other users. Utilizes blockchain to store results.">
    <meta name="author" content="Quang Bui, Vu Dao, Ryan Gunawan, Brian Sun, Samantha Wu">

    <title>CS480: Blockchain Poll</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/simple-sidebar.css" rel="stylesheet">

    <!-- Other CSS -->
    <link href="other.css" rel="stylesheet">
    <link href="graph.css" rel="stylesheet">

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
                    <a href="../creator/index.html">Create</a>
                </li>
                <li>
                    <a href="../voter/index.php">Vote</a>
                </li>
                <li>
                    <a href="#">Results</a>
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
                <div class="row buffer">
                    <div class="col-lg-12">
                        <h1>Results</h1>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                    </div>
                </div>
                <div class="row buffer">

                <?php

                // get stream items
                $port = trim(file_get_contents('../config/port'));
                $rpcusername = trim(file_get_contents('../config/rpc_username'));//"multichainrpc";
                $rpcpassword = trim(file_get_contents('../config/rpc_password'));//"EktTXuc9EP3nKVD2GZVW2JJdxBVUTswc1YfC1mFpino2";

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
                $poll_name = "`" . $creator . ":" . $poll . "`";
                $sql = "SELECT * FROM " . $poll_name;
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
                echo "<br><br>";
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
                    echo $current;
                    echo "<br><br>";
                }

                // table
                echo "<div id='wrapper'>";
                echo "<div class='chart'>";
                echo "<h2>Graph</h2>";
                echo "<table id='data-table' border='1' cellpadding='10' cellspacing='0'>";
                echo "<caption>Votes</caption>";
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
                echo "</div>";

                ?>
                </table>
                </div>
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
