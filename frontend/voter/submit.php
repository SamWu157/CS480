<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blockchain - Submit Vote</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/business-casual.css" rel="stylesheet">
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
                        <a href="index.php">Vote</a>
                    </li>
                    <li>
                        <a href="../results/index.php">Results</a>
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
                        <strong>Submit</strong> Vote
                    </h2>
                    <hr>
                </div>
                <div class="col-lg-12 text-center">
                    <br>

                    <?php

                    // get information
                    $poll_name = $_POST["poll_name"];
                    $creator = $_POST["creator"];
                    $choice = $_POST["opt"];
                    $voter = $_POST["voter"];
                    $voter = strtolower($voter);

                    if (empty($voter)) {
                        echo "<b>Error: </b>ID cannot be empty";
                    } elseif (!ctype_alnum(str_replace(' ', '', $voter))) {
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
                            $valid = FALSE;

                            // connect
                            $conn = new mysqli($servername, $username, $password, $database);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error());
                            } else {

                                // query
                                $table = $creator . ":" . $poll_name;
                                $table = strtolower($table);
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
                                $voter_table = strtolower($voter_table);
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
                                    echo "<b>Creator ID: </b>" . $creator . "<br>";
                                    echo "<b>Voter ID: </b>" . $voter . "<br>";
                                    echo "<b>Choice ID: </b>" . $choice_id . "<br>";
                                    echo "<b>Choice: </b>" . $choice . "<br>";

                                    // add to voter database
                                    $sql = "INSERT INTO `" . $voter_table . "` (id) VALUES ('" . $voter . "')";
                                    $conn->query($sql);
                                    
                                    // publish to stream
                                    $port = trim(file_get_contents('../config/port'));
                                    $rpcusername = trim(file_get_contents('../config/rpc_username'));
                                    $rpcpassword = trim(file_get_contents('../config/rpc_password')); 

                                    $a = 'curl -s --user ' . $rpcusername . ':' . $rpcpassword . ' --data-binary \'';
                                    $b = '{"jsonrpc": "1.0", "id":"curltest", "method": "publish", "params": ["' . $table . '", "' . $voter . '", "' . $choice_id . '"';
                                    $c = '] }\' -H "content-type: text/plain;" http://127.0.0.1:' . $port . '/';
                                    $cmd = $a . $b . $c;

                                    $ret= exec($cmd);
                                    $rets = json_decode($ret, true);
                                    $valid = TRUE;
                                } else {
                                    echo "Voter ID already voted";
                                }
                            }
                            $conn->close();
                        }
                    }
                    ?>



                    <br>
                    <br>
                    <img class="img-responsive img-border img-full" src="../img/vote.jpg" alt="">
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
