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
                    <a href="index.php">Vote</a>
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
                <div class="row buffer">
                    <div class="col-lg-12">
                        <h1>Vote</h1>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
                    </div>
                </div>
                <div class="row buffer">
                <h3>Vote Submission</h3>
                <?php

                // get information
                $poll_name = $_POST["poll_name"];
                $creator = $_POST["creator"];
                $choice = $_POST["opt"];
                $voter = $_POST["voter"];

                if (empty($voter)) {
                    echo "<b>Error: </b>ID cannot be empty";
                } elseif (!ctype_alnum($voter)) {
                    echo "<b>Error: </b>ID can only contain alphanumeric characters";
                } else {
                    if (empty($choice)) {
                        echo "<b>Error: </b>Must pick an option";
                    } else {

                        // get choice id
                        $servername = "localhost";
                        $username = "cs480";
                        $password = "password";
                        $database = "polls";

                        // connect
                        $conn = new mysqli($servername, $username, $password, $database);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error());
                        } else {

                            // query
                            $table = $creator . ":" . $poll_name;
                            $sql = "SELECT id FROM `" . $table . "` WHERE opt='" . $choice . "'";
                            $result = $conn->query($sql);
                            $choice_id = "";
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $choice_id = $row["id"];
                                }
                            }

                            // hash values
                            $voter = hash('sha256', $voter);

                            // check voters
                            $voter_table = "vote:" . $creator . ":" . $poll_name;
                            $voter_list = array();
                            $voter_valid = true;
                            $sql = "SELECT * FROM `" . $voter_table . "`";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $voter_list[] = $row["id"];
                                }
                            }


                            if (!in_array($voter, $voter_list)) {
                                echo "<b>Poll: </b>" . $poll_name . "<br>";
                                echo "<b>Creator ID:</b>" . $creator . "<br>";
                                echo "<b>Voter ID: </b>" . $voter . "<br>";
                                echo "<b>Choice ID: </b>" . $choice_id . "<br>";
                                echo "<b>Choice: </b>" . $choice . "<br>";

                                // add to voter database
                                $sql = "INSERT INTO `" . $voter_table . "` (id) VALUES ('" . $voter . "')";

                                // publish to stream
                                $port = "6750";
                                $rpcusername = "multichainrpc";
                                $rpcpassword = "EktTXuc9EP3nKVD2GZVW2JJdxBVUTswc1YfC1mFpino2";

                                $a = 'curl -s --user ' . $rpcusername . ':' . $rpcpassword . ' --data-binary \'';
                                $b = '{"jsonrpc": "1.0", "id":"curltest", "method": "publish", "params": ["' . $poll_name . '", "' . $voter . '", "' . $choice_id . '"';
                                $c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:' . $port . '/';
                                $cmd = $a . $b . $c;

                                $ret=system($cmd);
                                $rets = json_decode($ret, true);
                               
                            } else {
                                echo "Voter ID already voted";
                            }
                            $conn->query($sql);
                        }
                        $conn->close();
                    }
                }
                echo '<br>';
                echo "<input type='button' value='back' class='buffer' onClick=window.location='../index.html'";
                ?>
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
