    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Blockchain Poll - Vote</title>

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
                            <strong>Vote</strong>
                        </h2>
                        <hr>
                    </div>
                    <div class="col-lg-12 text-center">

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
                        //
                        // get information
                        $poll = $_GET['poll'];
                        $creator = $_GET['creator'];
                        $table = $creator . ":" . $poll;
                        $table = strtolower($table);
                        echo $table;

                        // query for poll information
                        $sql = "SELECT * FROM `". $table . "`";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // display poll
                            echo "<h3>" . $poll . " by " . $creator . "</h3>";
                            echo "<br>";
                            
                            echo "<form action='submit.php' method='post'>" .
                                "<h4>Voter ID: <input type='text' name='voter'></h4>";
                            echo "<input type='hidden' name='poll_name' " .
                                "value='" . $poll . "'>";
                            echo "<input type='hidden' name='creator' " .
                                "value='" . $creator . "'>";
                            echo "<ul>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<li class='buffer'>" .
                                    "<input type='radio' name='opt'" .
                                    " value='" . $row["opt"] . "'>" .
                                    "<h5>" . $row["opt"] . "</h5>" .
                                    "</li>";
                        }
                        echo "</ul> <input class='btn btn-default' type='submit' value='submit'>" .
                            "</form>";
                    } else {
                        echo "Error: poll not found";
                    }

                    $conn->close();
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
