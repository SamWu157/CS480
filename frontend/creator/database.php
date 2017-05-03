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

// sql to create table
if (empty($_POST["poll_name"])) {
    echo "Error: Poll Name cannot be empty";
} else {
    $counter = $_POST["counter"];
    $option = array();
    $valid = true;
    for ($x = 1; $x <= $counter; $x++) {
        $current = $_POST["option_" . (string)$x];
        if (empty($current)) {
            $valid = false;
            break;
        } else {
            $option[] = $current;
        }
    }
    if ($valid) {
        $table = "`" . $_POST["poll_name"] . "`";
        $sql = "CREATE TABLE " . $table . " (opt TEXT)";
        if ($conn->query($sql) === TRUE) {
            echo "TABLE " . $table . " created successfully\n";
            // init table
            foreach ($option as $o) {
                $sql = "INSERT INTO " . $table . "(opt) VALUES ('" . $o . "')"; 
                $conn->query($sql);
            }
        } else {
            echo "Error creating table: " . $conn->error;
        }
    } else {
        echo "Error: empty option";
    }
}

$conn->close();
?>
