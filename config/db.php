<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tours_travel_db';

$connection = @mysqli_connect($host, $user, $pass, $dbname);

if ($connection) {
    mysqli_set_charset($connection, 'utf8mb4');
    $db_status = 'connected';
} else {
    $db_status = 'not-configured';
    $db_error = mysqli_connect_error();
}
?>
