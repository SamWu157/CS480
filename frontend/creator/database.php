<?php
$servername = "localhost";
$username = "cs480";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error());
}

// Create database
$database = "polls";
$sql = "CREATE DATABASE IF NOT EXISTS " . $database;
if ($conn->query($sql) == TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
    echo "\n";
}
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error());
    echo "\n";
}

// sql to create table
$table = "`test poll`";
$sql = "CREATE TABLE " . $table . " (opt TEXT)";
if ($conn->query($sql) === TRUE) {
    echo "TABLE " . $table . " created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    echo "\n";
}

// init table
$option = array("option 1", "option 2");
foreach ($option as $o) {
    $sql = "INSERT INTO " . $table . "(opt) VALUES ('" . $o . "')"; 
    $conn->query($sql);
}


$conn->close();
?>
