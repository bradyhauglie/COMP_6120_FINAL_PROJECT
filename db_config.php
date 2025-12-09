<?php

function getDBConnection() {
    $conn = new mysqli('mysqllab.auburn.edu', 'bah0083', '7Mms44374/', 'bah0083db');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>
