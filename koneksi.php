<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sepakbola";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function base_url($url = null)
{
    $base_url = 'http://localhost/presensi';

    if ($url != null) {
        return $base_url . '/' . $url;
    } else {
        return $base_url;
    }
}
