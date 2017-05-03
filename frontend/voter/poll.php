<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Simple Sidebar - Start Bootstrap Template</title>

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
                <!-- temporary poll 
                <div class="row buffer">
                    <h3>Temporary Poll</h3>
                    <ul>
                        <li class="buffer">
                            <input type="radio" name="option">
                            <h5>Option 1</h5>
                        </li>
                        <li class="buffer">
                            <input type="radio" name="option">
                            <h5>Option 2</h5>
                        </li>
                    </ul>
                    <input type="button" value="submit" class= "buffer" id="submit">
                </div>
                -->
                <div class="row buffer">
                <?php
                $servername = "localhost";
                $username = "cs480";
                $password = "password";
                $database = "polls";

                // connect
                $conn = new mysqli($servername, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error());
                }

                // tables query
                $poll = $_GET['poll'];
                $sql = "SELECT * FROM `". $poll . "`";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<form action='submit.php' method='post'>" .
                        "<h3>Voter ID: <input type='text' name='voter'></h3>" .
                        "<h3>" . $poll . "</h3>" .
                        "<input type='hidden' name='poll_name'" .
                        " value='" . $poll . "'>" .
                        "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li class='buffer'>" .
                            "<input type='radio' name='opt'" .
                            " value='" . $row["opt"] . "'>" .
                            "<h5>" . $row["opt"] . "</h5>" .
                            "</li>";
                    }
                    echo "</ul> <input type='submit' value='submit'>" .
                        "</form>";
                } else {
                    echo "Error: poll not found";
                }

                $conn->close();
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
