<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="description" content="A polling app which allows users to create polls and vote on polls created by other users. Utilizes blockchain to store results">
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
                <h3>Vote Submitted</h3>
                <p>*need to add blockchain integration</p>
                <?php
                $servername = "localhost";
                $username = "cs480";
                $password = "password";
                $database = "polls";

                $conn = new mysqli($servername, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error());
                }

                // get info
                $poll_name = $_GET["poll"];
                $creator = $_GET["creator"];
                $table = $creator . ":" . $poll_name;

                // delete table
                $sql = "DROP TABLE `" . $table . "`";
                if ($conn->query($sql) === TRUE) {
                } else {
                    echo "<b>Error: </b>" . $conn->error;
                }

                // remove from creators table
                $sql = "DELETE FROM creators WHERE poll='" 
                    . $poll_name . "'";
                if ($conn->query($sql) === TRUE) {
                    echo "<b>Poll Deleted: </b>" . $table;
                } else {
                    echo "<b>Error: </b>" . $conn->error;
                }

                echo "<br>";
                echo "<input type='button' value='back' class='buffer' onClick=window.location='../index.html'>";
                echo "<input type='button' value='vote' class='buffer' onClick=window.location='index.php'>";
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
