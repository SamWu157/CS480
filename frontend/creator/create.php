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
                $option = array();
                $valid = true;

                // check creator id
                if (empty($creator)){
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Creator ID cannot be empty";
                    echo "</div>";
                }

                // check poll name
                if (empty($poll_name)){
                    $valid = false;
                    echo "<div class='row buffer'>";
                    echo "<b>Error: </b>Poll Name cannot be empty";
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
                    } else {
                        $option[] = $current;
                    }
                }

                // create tables
                if ($valid) {
                    echo "<div class='row buffer'>";

                    // add to id table
                    $sql = "INSERT INTO creators (id, poll) VALUES ('" . $creator . "', '" . $poll_name . "')"; 
                    if ($conn->query($sql) === FALSE) {
                        echo "<b>Error creating tables</b>: " . $conn->error; 
                    } else {

                        // create poll table
                        $table = "`" . $creator . ":" . $poll_name . "`";
                        $sql = "CREATE TABLE " . $table . " (opt TEXT)";
                        if ($conn->query($sql) === TRUE) {
                            // init table
                            foreach ($option as $o) {
                                $sql = "INSERT INTO " . $table . "(opt) VALUES ('" . $o . "')";
                                $conn->query($sql);
                            }
                            // success
                            echo "<b>Poll Created: </b>" . $_POST["poll_name"];
                        } else {
                            echo "<b>Error creating table</b>: " . $conn->error;
                        }
                    }

                    echo "</div>";
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
